<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\DBALException;
use Geekhub\UserBundle\UserProvider\FacebookProvider;
use Geekhub\UserBundle\UserProvider\VkontakteProvider;
use Geekhub\UserBundle\UserProvider\OdnoklassnikiProvider;

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
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
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
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        if (null === $user) {
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
            try {
                $this->userManager->updateUser($user);
            } catch (DBALException $e) {
                var_dump("dbal exception");
            }

            return $user;
        }

        $user = parent::loadUserByOAuthUserResponse($response);

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
