<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.03.14
 * Time: 22:57
 */

namespace Geekhub\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Message;

class EmailSubscriber implements EventSubscriber
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
            'postPersist',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof User) {
            $dispatcher = $this->container->get('hip_mandrill.dispatcher');

            $message = new Message();

            $message->setFromEmail('test@gmail.com')
                ->setFromName('Черкаська мрія')
                ->addTo($object->getEmail())
                ->setSubject('REGISTRATION')
                ->setHtml('<html><body><h1>DONE!!!</h1></body></html>')
            ;

            $dispatcher->send($message);
        }
    }
}
