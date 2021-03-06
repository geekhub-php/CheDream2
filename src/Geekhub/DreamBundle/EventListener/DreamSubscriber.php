<?php

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\AbstractContribute;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class DreamSubscriber implements EventSubscriber
{
    /** @var Container $container */
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
            'postPersist',
            'preRemove',
        );
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        /** @var Logger $logger */
        $logger  = $this->container->get('logger');
        /** @var UserInterface $user */
        $user    = $this->container->get('security.context')->getToken()->getUser();
        /** @var Request $request */
        $request = $this->container->get('request');

        if (!is_object($user) || !in_array('Symfony\Component\Security\Core\User\UserInterface', class_implements($user))) {
            $logger->addError(
                sprintf('Something that not user, try to delete object "%s", with id: "%s", from ip: "%s", from uri: "%s"', get_class($object), $object->getId(), $request->getClientIp(), $request->getUri())
            );
            throw new AccessDeniedException('Something went wrong, but don\'t worry - we already work on it!');
        }

        $logger->addWarning(
            sprintf('User with username: "%s", delete object "%s", with id: "%s", from ip: "%s", from uri: "%s"', $user->getUsername(), get_class($object), $object->getId(), $request->getClientIp(), $request->getUri())
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

        if (isset($users)) {
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
    }

    protected function getTemplate($nameTwigTemplate, $options = array())
    {
        return $this->container->get('templating')->render(
            $nameTwigTemplate,
            $options
        );
    }
}
