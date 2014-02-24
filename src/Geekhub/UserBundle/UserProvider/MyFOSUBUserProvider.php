<?php

namespace Geekhub\UserBundle\UserProvider;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface,
    HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\DBAL\Types,
    Doctrine\DBAL\DBALException;
use Geekhub\UserBundle\UserProvider\FacebookUserDataService,
    Geekhub\UserBundle\UserProvider\VkontakteUserDataService,
    Geekhub\UserBundle\UserProvider\OdnoklassnikiUserDataService;
 
class MyFOSUBUserProvider extends BaseClass implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /** @var FacebookUserDataService $facebookUserDataService */
    protected $facebookUserDataService;

    /** @var VkontakteUserDataService $vkontakteUserDataService */
    protected $vkontakteUserDataService;

    /** @var OdnoklassnikiUserDataService $odnoklassnikiUserDataService */
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
        $setterId = $setter.'Id';
        $setterToken = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setterId(null);
            $previousUser->$setterToken(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setterId($username);
        $user->$setterToken($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setterId = $setter.'Id';
            $userDataServiceName = lcfirst($service).'UserDataService';

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
            try {
                $this->userManager->updateUser($user);
            }
            catch (DBALException $e) {
                var_dump("dbal exception");
            }

            return $user;
        }
 
        $user = parent::loadUserByOAuthUserResponse($response);

        return $user;
    }

    public function setFacebookUserDataService(FacebookUserDataService $service)
    {
        $this->facebookUserDataService = $service;
    }

    public function setVkontakteUserDataService(VkontakteUserDataService $service)
    {
        $this->vkontakteUserDataService = $service;
    }

    public function setOdnoklassnikiUserDataService(OdnoklassnikiUserDataService $service)
    {
        $this->odnoklassnikiUserDataService = $service;
    }
}
