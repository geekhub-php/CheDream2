<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.04.14
 * Time: 23:36
 */

namespace Geekhub\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Geekhub\UserBundle\Entity\User;

class UserDoctrineSubscriber implements EventSubscriber
{
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
//        $entityManager = $args->getEntityManager();

        if ($entity instanceof User) {
            if ($eventArgs->hasChangedField('email')) {
                $entity->setEmail($eventArgs->getOldValue('email'));
                $fake = $entity->isFakeEmail();
                $entity->setEmail($eventArgs->getNewValue('email'));
                if ($fake) {
                    var_dump($fake, $eventArgs->getOldValue('email'), $eventArgs->getNewValue('email'), $entity->getEmail());
                    exit;
                }
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof User && !$object->isFakeEmail()) {
            var_dump($object->getEmail());exit;
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'prePersist',
        );
    }
}