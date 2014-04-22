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

        if (!$sc = $this->container->get('security.context')) {
            return;
        }

        $user = $sc->getToken()->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $request = $this->container->get('request');
        $url = $this->container->get('router')->generate(self::UPDATE_CONTACTS_ROUTE);

        if ((!$user->getPhone() || !$user->getEmail()) && $request->get('_route') !== self::UPDATE_CONTACTS_ROUTE) {
            $event->setResponse(new RedirectResponse($url));
        } elseif ($email = $request->query->get('mergeUserWithEmail')) {
            $hasUser = $this->container->get('doctrine')->getRepository('GeekhubUserBundle:User')->findOneByEmail($email);

            if (empty($hasUser->getNotNullSocialIds())) {
                throw new \Exception(sprintf('Oops! Something went wrong - user with email "%s" not register by
                social networks', $hasUser->getEmail()));
            }

            $socialNetworks = $hasUser->getNotNullSocialIds();
            $resourceOwner = $this->container->get(sprintf("hwi_oauth.resource_owner.%s", key($socialNetworks)));

//            $redirectUrl = $this->container->get('security.http_utils')->generateUri($request,
//                self::UPDATE_CONTACTS_ROUTE);
//            var_dump($redirectUrl);exit;

            $this->container->get('hwi_oauth.connect_controller')->redirectToServiceAction($request,
                key($socialNetworks));



//            var_dump($resourceOwner->getAuthorizationUrl($url)); exit;
//            $event->setResponse(new RedirectResponse($resourceOwner->getAuthorizationUrl($url)));
//            echo $resourceOwner->getAuthorizationUrl($url)."<br/>".$url;

//            $this->container->get('account_merger')->mergeAccountsByEmail($email, $user);
        }
    }
}
