<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface,
    Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Types,
    Doctrine\DBAL\DBALException;
use Geekhub\UserBundle\UserProvider\FacebookProvider,
    Geekhub\UserBundle\UserProvider\VkontakteProvider,
    Geekhub\UserBundle\UserProvider\OdnoklassnikiProvider;

class DreamUserProvider extends BaseClass implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /** @var FacebookProvider $facebookProvider */
    protected $facebookProvider;

    /** @var VkontakteProvider $vkontakteProvider */
    protected $vkontakteProvider;

    /** @var OdnoklassnikiProvider $odnoklassnikiProvider */
    protected $odnoklassnikiProvider;

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setterId = $setter.'Id';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->loadUserByUsername($username)) {
            $previousUser->$setterId(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setterId($username);

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->loadUserByUsername($username);

        if (null === $user || null === $username) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setterId = $setter.'Id';
            $userDataServiceName = lcfirst($service).'Provider';

            $user = $this->userManager->createUser();
            $user->$setterId($username);

            // I use different setters setters for each type of oath providers:
            // for example setVkontakteUser(...),  setFacebookUser(...)
            // the actual name of setter is in the variable $setterUser.
            $user = $this->$userDataServiceName->setUserData($user, $response);
            $user->setUsername($username);
            //$user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $userController = $this->facebookProvider->getContainer()->get('geekhub.user.user_controller');
            $request =  new Request();
            $response = $userController->registerAction($request, $user);
            var_dump($response);
            exit;
            /*
            try {
                $this->userManager->updateUser($user);
            } catch (DBALException $e) {
                var_dump("dbal exception");
            }

            return $user;*/
        }

        $user = parent::loadUserByOAuthUserResponse($response);
        if (!$user->isAccountNonLocked()) {
            throw new LockedException();
        }

        return $user;
    }

    public function setFacebookProvider(FacebookProvider $facebookProvider)
    {
        $this->facebookProvider = $facebookProvider;
    }

    public function setVkontakteProvider(VkontakteProvider $vkontakteProvider)
    {
        $this->vkontakteProvider = $vkontakteProvider;
    }

    public function setOdnoklassnikiProvider(OdnoklassnikiProvider $odnoklassnikiProvider)
    {
        $this->odnoklassnikiProvider = $odnoklassnikiProvider;
    }
}
