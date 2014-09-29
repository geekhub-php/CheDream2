<?php

namespace Geekhub\UserBundle\Controller;

use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseConnectController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ConnectController extends BaseConnectController
{
    /**
     * @param Request $request
     * @param string  $service
     *
     * @return RedirectResponse
     */
    public function redirectToServiceAction(Request $request, $service)
    {
        // Check for a specified target path and store it before redirect if present
        $param = $this->container->getParameter('hwi_oauth.target_path_parameter');

        if ($request->hasSession()) {
            // initialize the session for preventing SessionUnavailableException
            $session = $request->getSession();
            $session->start();

            $providerKey = $this->container->getParameter('hwi_oauth.firewall_name');
            $request->getSession()->set('_security.' . $providerKey . '.target_path', $request->headers->get('referer'));
        }

        return new RedirectResponse($this->container->get('hwi_oauth.security.oauth_utils')->getAuthorizationUrl($request, $service));
    }
}
