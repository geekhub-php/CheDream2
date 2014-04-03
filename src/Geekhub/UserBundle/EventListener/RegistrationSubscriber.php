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
use Symfony\Component\DependencyInjection\Container;

class RegistrationSubscriber implements EventSubscriber
{
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
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
            $body = $this->container->get('templating')->render(
                'GeekhubResourceBundle:Email:registration.html.twig',
                array(
                    'user' => $object->getFirstName()." ".$object->getLastName()
                )
            );

            $message->setFromEmail('test@gmail.com')
                ->setFromName('Черкаська мрія')
                ->addTo($object->getEmail())
                ->setSubject('REGISTRATION')
                ->setHtml($body)
            ;

            $dispatcher->send($message);
        }
    }
}
