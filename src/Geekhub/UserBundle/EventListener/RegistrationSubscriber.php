<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.03.14
 * Time: 22:57
 */

namespace Geekhub\UserBundle\EventListener;

use Geekhub\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Router;

class RegistrationSubscriber
{
    const UPDATE_CONTACTS_ROUTE = 'profile_update_contacts';

    protected $ignoredRoutes = [];
    protected $container;
    
    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $path = $this->container->get('router')->getContext()->getPathInfo();
        if (strpos($path, '/connect') !== false || strpos($path, '/login-social') !== false) {
            return;
        }

        if (!$sc = $this->container->get('security.context')) {
            return;
        }

        $user = $sc->getToken()->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $request = $this->container->get('request');
        $updateContactsAction = $this->container->get('router')->generate(self::UPDATE_CONTACTS_ROUTE, array(), Router::ABSOLUTE_URL);

        $apendEmail = explode('@', trim($user->getEmail()));

        if ($apendEmail[1] == 'example.com' && $request->get('_route') !== self::UPDATE_CONTACTS_ROUTE) {
            $event->setResponse(new RedirectResponse($updateContactsAction));
        }
    }
}
