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
            'postLoad'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Dream) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            $object->setAuthor($user);
            $object->addStatus(new Status(Status::SUBMITTED));
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof Dream) {
            $tagManager = $this->container->get('geekhub.tag.tag_manager');
            $tagManager->loadTagging($object);
        }
    }
}
