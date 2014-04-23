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

        if (!$sc = $this->container->get('security.context')) {
            return;
        }

        $user = $sc->getToken()->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $request = $this->container->get('request');
        $url = $this->container->get('router')->generate(self::UPDATE_CONTACTS_ROUTE, [], Router::ABSOLUTE_URL);

        if ((!$user->getEmail() || false !== strpos($user->getEmail(), '@example.com')) && $request->get('_route') !== self::UPDATE_CONTACTS_ROUTE) {
            $event->setResponse(new RedirectResponse($url));
        } elseif ($accessToken = $request->query->get('code')) {
            $service = $request->query->get('service');
            $this->container->get('session')->set('_hwi_oauth.connect_confirmation.key', $accessToken);
            $event->setResponse($this->container->get('hwi_oauth.connect_controller')->connectServiceAction($request, $service));
        } elseif ($email = $request->query->get('mergeUserWithEmail')) {
            $hasUser = $this->container->get('doctrine')->getRepository('GeekhubUserBundle:User')->findOneByEmail($email);

            $socialIds = $hasUser->getNotNullSocialIds();
            if (empty($socialIds)) {
                throw new \Exception(sprintf('Oops! Something went wrong - user with email "%s" not register by
                social networks', $hasUser->getEmail()));
            }

            $socialNetworks = $hasUser->getNotNullSocialIds();
            $resourceOwner = $this->container->get(sprintf("hwi_oauth.resource_owner.%s", key($socialNetworks)));
//            var_dump($resourceOwner->getAuthorizationUrl($url)); exit;

//            $redirectUrl = $this->container->get('security.http_utils')->generateUri($request,
//                self::UPDATE_CONTACTS_ROUTE);
//            var_dump($redirectUrl);exit;

//            var_dump($resourceOwner->getAuthorizationUrl($url)); exit;

//            $this->container->get('hwi_oauth.connect_controller')->connectServiceAction($request, key($socialNetworks));

            $url = $this->container->get('router')->generate(self::UPDATE_CONTACTS_ROUTE, ['service' => key($socialNetworks)], Router::ABSOLUTE_URL);
            $event->setResponse(new RedirectResponse($resourceOwner->getAuthorizationUrl($url)));



//            echo $resourceOwner->getAuthorizationUrl($url)."<br/>".$url;

//            $this->container->get('account_merger')->mergeAccountsByEmail($email, $user);
        }
    }
}
