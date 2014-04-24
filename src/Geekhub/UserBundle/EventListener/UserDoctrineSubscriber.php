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
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;
use Symfony\Component\DependencyInjection\Container;

class UserDoctrineSubscriber implements EventSubscriber
{
    protected $mandrillDispatcher;

    protected $container;

    public function __construct(Dispatcher $mandrillDispatcher, Container $container)
    {
        $this->mandrillDispatcher = $mandrillDispatcher;
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
            'preUpdate',
            'prePersist',
        );
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if ($entity instanceof User) {
            if ($eventArgs->hasChangedField('email')) {
                $entity->setEmail($eventArgs->getOldValue('email'));
                $fake = $entity->isFakeEmail();
                $entity->setEmail($eventArgs->getNewValue('email'));
                if ($fake) {
                    //this is sending registration email to new user, that had fake email and chenge to not fake
                    $this->sendEmail($entity);
                }
            }
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof User && !$object->isFakeEmail()) {
            //this is sending registration email to new user, that has not fake email
            $this->sendEmail($object);
        }
    }

    /**
     * @param User $user
     */
    protected function sendEmail(User $user)
    {
        $message = new Message();
        $body = $this->container->get('templating')->render(
            'GeekhubResourceBundle:Email:registration.html.twig',
            array(
                'user' => $user
            )
        );

        $message->setFromEmail('test@gmail.com')
            ->setFromName('Черкаська мрія')
            ->addTo($user->getEmail())
            ->setSubject('REGISTRATION')
            ->setHtml($body)
        ;

        $this->mandrillDispatcher->send($message);
    }
}
