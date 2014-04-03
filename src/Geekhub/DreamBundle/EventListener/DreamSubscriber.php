<?php

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream,
    Geekhub\DreamBundle\Entity\Status;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;
use Symfony\Component\DependencyInjection\Container;

class DreamSubscriber implements EventSubscriber
{
    protected $container;

    protected $dispatcher;

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
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
            $dream = $object->getDream();
            $author = $dream->getAuthor();
            $contributors = $this->container->get('doctrine')
                ->getRepository('GeekhubDreamBundle:Dream')
                ->getArrayContributorsByDream($dream)
            ;
            $template = $this->container->get('templating');
            $admin = $this->container->getParameter('admin.mail');

            switch ($object->getTitle()) {
                case Status::SUBMITTED :
                    $this->sendEmail(
                        $template->render(
                            'GeekhubResourceBundle:Email:dreamSubmitted.html.twig',
                            array(
                                'dream' => $dream
                            )
                        ),
                        $admin,
                        Status::SUBMITTED
                    );
                    break;
                case Status::COLLECTING_RESOURCES :
                    $this->sendEmail(
                        $template->render(
                            'GeekhubResourceBundle:Email:dreamCollecting.html.twig',
                            array(
                                'dream' => $dream
                            )
                        ),
                        $author->getEmail(),
                        Status::COLLECTING_RESOURCES
                    );
                    break;
                case Status::REJECTED :
                    $this->sendEmail(
                        $template->render(
                            'GeekhubResourceBundle:Email:dreamRejected.html.twig',
                            array(
                                'dream' => $dream
                            )
                        ),
                        $author->getEmail(),
                        Status::REJECTED
                    );
                    break;
                case Status::IMPLEMENTING :
                    foreach ($contributors as $contributor) {
                        $this->sendEmail(
                            $template->render(
                                'GeekhubResourceBundle:Email:dreamImplementing.html.twig',
                                array(
                                    'dream' => $dream
                                )
                            ),
                            $contributor->getEmail(),
                            Status::IMPLEMENTING
                        );
                    }
                    break;
                case Status::COMPLETED :
                    $this->sendEmail(
                        $template->render(
                            'GeekhubResourceBundle:Email:dreamCompleted.html.twig',
                            array(
                                'dream' => $dream
                            )
                        ),
                        $admin,
                        Status::COMPLETED
                    );
                    break;
                case Status::SUCCESS :
                    foreach ($contributors as $contributor) {
                        $this->sendEmail(
                            $template->render(
                                'GeekhubResourceBundle:Email:dreamSuccess.html.twig',
                                array(
                                    'dream' => $dream
                                )
                            ),
                            $contributor->getEmail(),
                            Status::SUCCESS
                        );
                    }
                    break;
                case Status::FAIL :
                    $this->sendEmail(
                        $template->render(
                            'GeekhubResourceBundle:Email:dreamFail.html.twig',
                            array(
                                'dream' => $dream
                            )
                        ),
                        $author->getEmail(),
                        Status::FAIL
                    );
                    break;
            }
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

        $this->dispatcher->send($message);
    }
}
