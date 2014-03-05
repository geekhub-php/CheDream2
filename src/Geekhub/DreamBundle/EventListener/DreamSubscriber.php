<?php

namespace Geekhub\DreamBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream,
    Geekhub\DreamBundle\Entity\Status;

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
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $dream = $args->getObject();

        if ($dream instanceof Dream) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            $dream->setAuthor($user);
            $dream->addStatus(new Status(Status::SUBMITTED));
        }
    }
}
