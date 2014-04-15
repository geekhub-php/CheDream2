<?php

namespace Geekhub\DreamBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;
use Hip\MandrillBundle\Dispatcher;
use Symfony\Component\DependencyInjection\Container;

class DreamSubscriber implements EventSubscriber
{
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
//            'postPersist'
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
}
