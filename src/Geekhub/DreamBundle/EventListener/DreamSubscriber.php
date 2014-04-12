<?php

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;
use Symfony\Component\DependencyInjection\Container;

class DreamSubscriber implements EventSubscriber
{
    protected $container;

    protected $mandrillDispatcher;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setMandrillDispatcher(Dispatcher $dispatcher)
    {
        $this->mandrillDispatcher = $dispatcher;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'postPersist'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Dream) {
            $object->addStatus(new Status(Status::SUBMITTED));
            $token = $this->container->get('security.context')->getToken();

            if (null != $token) {
                $object->setAuthor($token->getUser());
            } elseif (!$object->getAuthor()) {
                throw new \Exception("Ooops! Something went wrong. We can't create dream without user. Please contact with administrator.");
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Status) {
            $this->postStatusEmail($object->getTitle(), $object->getDream());
        }

        if ('Geekhub\DreamBundle\Entity\AbstractContribute' == get_parent_class($object)) {
            $this->sendEmail(
                $this->getTemplate(
                    'GeekhubResourceBundle:Email:contribution.html.twig',
                    array(
                        'dream' => $object->getDream(),
                        'contributor' => $object
                    )
                ),
                $object->getUser()->getEmail(),
                'Підтримка мрії'
            );

        }
    }

    protected function sendEmail($body, $to, $subject)
    {
        $message = new Message();
        $message->setFromEmail('test@gmail.com')
            ->setFromName('Черкаська мрія')
            ->addTo($to)
            ->setSubject($subject)
            ->setHtml($body)
        ;

        $this->mandrillDispatcher->send($message);
    }

    protected function postStatusEmail($status, Dream $dream)
    {
        if (in_array($status, array('success', 'implementing'))) {
            $users = $this->container->get('doctrine')
                ->getRepository('GeekhubDreamBundle:Dream')
                ->getArrayContributorsByDream($dream)
            ;
        }

        if (in_array($status, array('submitted', 'completed'))) {
            $user = new User();
            $user->setEmail($this->container->getParameter('admin.mail'));
            $users = array($user);
        }

        if (in_array($status, array('rejected', 'collecting-resources', 'fail'))) {
            $users = array($dream->getAuthor());
        }

        foreach ($users as $user) {
            $this->sendEmail(
                $this->getTemplate(
                    'GeekhubResourceBundle:Email:'.$status.'.html.twig',
                    array(
                        'dream' => $dream,
                        'contributor' => $user
                    )
                ),
                $user->getEmail(),
                $status
            );
        }
    }

    protected function getTemplate($nameTwigTemplate, $options = array())
    {
        return $this->container->get('templating')->render(
            $nameTwigTemplate,
            $options
        );
    }
}
