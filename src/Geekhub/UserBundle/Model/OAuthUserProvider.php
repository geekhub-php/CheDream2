<?php

namespace Geekhub\UserBundle\Model;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {

    /**
     * @var mixed
     */
    protected $em;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var mixed
     */
    protected $repository;

    public function __construct(ManagerRegistry $registry, $className) {
        $this->em = $registry->getManager();
        $this->repository = $this->em->getRepository($className);
        $this->className = $className;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $username = $response->getUsername();
        $nickname = $response->getNickname();
        $realname = $response->getRealName();
        $email    = $response->getEmail();

        $resourceOwnerName = $response->getResourceOwner()->getName();

        $user = $this->repository->findOneBy(
            array('resource' => $resourceOwnerName, 'username' => $nickname)
        );

        if (null === $user) {
            $user = new $this->className();
            $user->setUsername($nickname);
            $user->setResourceUsername($username);
            $user->setResource($resourceOwnerName);
            $user->setRealname($realname);
            $user->setEmail($email);
            $user->setLastLogin(new \DateTime());
            $this->em->persist($user);
            $id = $this->em->flush();

        }

        return $user;
    }

    public function loadUserByUsername($username) {

        $user = $this->repository->findOneBy(array('username' => $username));
        if (!$user) {
            throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === $this->class || is_subclass_of($class, $this->class);
    }
}