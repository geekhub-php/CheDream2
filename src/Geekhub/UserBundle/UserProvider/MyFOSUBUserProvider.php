<?php

namespace Geekhub\UserBundle\UserProvider;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider as BaseOAuthUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Doctrine\DBAL\Types;
 
class MyFOSUBUserProvider extends BaseClass implements UserProviderInterface, OAuthAwareUserProviderInterface
{

    protected $facebookUserDataService;

    protected $vkontakteUserDataService;

    protected $odnoklassnikiUserDataService;
 
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
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        //var_dump("in loadUserByOAuthUserResponse");
        //var_dump($response);

        $username = $response->getUsername();

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setterId = $setter.'Id';
            $setterToken = $setter.'AccessToken';
            $userDataServiceName = ucfirst($service).'UserDataService';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setterId($username);
            $user->$setterToken($response->getAccessToken());
            // I use different setters setters for each type of oath providers:
            // for example setVkontakteUser(...),  setFacebookUser(...)
            // the actual name of setter is in the variable $setterUser.
            $user = $this->get($userDataServiceName)->setUserData($user, $response);
            $user->setUsername($username);
            //$user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return $user;
        }
 
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setter($response->getAccessToken());
 
        return $user;
    }

    public function setFacebookUserDataService($service)
    {
        $this->facebookUserDataService = $service;
    }

    public function setVkontakteUserDataService($service)
    {
        $this->vkontakteUserDataService = $service;
    }

    public function setOdnoklassnikiUserDataService($service)
    {
        $this->odnoklassnikiUserDataService = $service;
    }
}