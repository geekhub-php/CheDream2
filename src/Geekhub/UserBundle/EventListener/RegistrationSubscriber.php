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

    public function onKernelRequest(GetResponseEvent $event)
    {
        //$user = $event->getAuthenticationToken()->getUser();
        $sc = $this->container->get('security.context');
        if ($sc->getToken()) {
            $user = $sc->getToken()->getUser();
            $targetRoute = 'profile_update_contacts';

            if($user instanceof User)
            {
                if (($user->getRegistrationStatus()==User::EMAIL_UNAVAILABLE) || (strstr($user->getEmail(),'@example.com'))) {
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
}
