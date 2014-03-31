<?php

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream,
    Geekhub\DreamBundle\Entity\Status;
use Hip\MandrillBundle\Message;

class DreamSubscriber implements EventSubscriber
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
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
        $dispatcher = $this->container->get('hip_mandrill.dispatcher');

        if ($object instanceof Status) {
            $dream = $object->getDream();
            $author = $dream->getAuthor();
            $contributors = $this->container->get('doctrine')
                ->getRepository('GeekhubDreamBundle:Dream')
                ->getArrayContributorsByDream($dream)
            ;
            $url = $this->container->get('router')
                ->generate('view_dream', array('slug' => $dream->getSlug()), true);

            switch ($object->getTitle()) {
                case Status::SUBMITTED :
                    $this->sendEmail(
                        $dispatcher,
                        "<html><body>
                            <p>
                                <a href='".$url."'>".$dream->getTitle()."</a> - створено!
                            </p>
                        </body></html>",
                        $this->container->getParameter('admin.mail'),
                        'STATUS'
                    );
                    break;
                case Status::COLLECTING_RESOURCES :
                    $this->sendEmail(
                        $dispatcher,
                        $dream->getTitle(). ' - розпочато збір коштів!',
                        $author->getEmail(),
                        'STATUS'
                    );
                    break;
                case Status::REJECTED :
                    $this->sendEmail(
                        $dispatcher,
                        "<html><body>
                            <p>
                                <a href='".$url."'>".$dream->getTitle()."</a> - повернуто на дооформлення.
                            </p>
                        </body></html>",
                        $author->getEmail(),
                        'STATUS'
                    );
                    break;
                case Status::IMPLEMENTING :
                    foreach ($contributors as $contributor) {
                        $this->sendEmail(
                            $dispatcher,
                            "<html><body>
                                <p>
                                    <a href='".$url."'>".$dream->getTitle()."</a> - розпочата реалізація.
                                </p>
                            </body></html>",
                            $contributor->getEmail(),
                            'STATUS'
                        );
                    }
                    break;
                case Status::COMPLETED :
                    $this->sendEmail(
                        $dispatcher,
                        "<html><body>
                            <p>
                                <a href='".$url."'>".$dream->getTitle()."</a> - завершена реалізація.
                            </p>
                        </body></html>",
                        $this->container->getParameter('admin.mail'),
                        'STATUS'
                    );
                    break;
                case Status::SUCCESS :
                    $this->sendEmail(
                        $dispatcher,
                        "<html><body>
                            <p>Вітаємо!</p>
                            <p>
                                Вашу мрію <a href='".$url."'>".$dream->getTitle()."</a> завершено.
                            </p>
                        </body></html>",
                        $author->getEmail(),
                        'STATUS'
                    );
                    break;
                case Status::FAIL :
                    $this->sendEmail(
                        $dispatcher,
                        "<html><body>
                            <p>
                                Мрію <a href='".$url."'>".$dream->getTitle()."</a> потрібно завершити.
                            </p>
                        </body></html>",
                        $author->getEmail(),
                        'STATUS'
                    );
                    break;
            }
        }
    }

    protected function sendEmail($dispatcher, $body, $to, $subject)
    {
        $message = new Message();
        $message->setFromEmail('test@gmail.com')
            ->setFromName('Черкаська мрія')
            ->addTo($to)
            ->setSubject($subject)
            ->setHtml($body)
        ;

        $dispatcher->send($message);
    }
}
