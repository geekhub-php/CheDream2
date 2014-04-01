<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.03.14
 * Time: 14:35
 */

namespace Geekhub\TagBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TagSubscriber implements EventSubscriber
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postLoad'
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (in_array('DoctrineExtensions\Taggable\Taggable', class_implements($object))) {
            $tagManager = $this->container->get('geekhub.tag.tag_manager');
            $tagManager->loadTagging($object);
        }
    }
}
