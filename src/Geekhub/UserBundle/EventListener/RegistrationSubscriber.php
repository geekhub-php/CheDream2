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
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationSubscriber implements EventSubscriber
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
    public function onKernelRequest(GetResponseEvent $event)
    {
        //$user = $event->getAuthenticationToken()->getUser();
        $sc = $this->container->get('security.context');
        $user = $sc->getToken()->getUser();
        $targetRoute = 'profile_update_contacts';

        if($user instanceof User)
        {
            if (!strstr($user->getEmail(),'@')) {
                $routeName = $this->container->get('request')->get('_route');
                $uri =$this->container->get('request')->getUri();
                if ($routeName && ($routeName != $targetRoute) && !strstr($uri,'/upload/')) {
                    $url = $this->container->get('router')->generate($targetRoute);
                    $event->setResponse(new RedirectResponse($url));
                }
            }
        }
    }
}
