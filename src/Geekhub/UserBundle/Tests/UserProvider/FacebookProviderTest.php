<?php

namespace Geekhub\UserBundle\Tests\UserProvider;

use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\FacebookProvider;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class FacebookProviderTest extends WebTestCase
{
    /**
     * @dataProvider getUserCredentialsData
     */
    public function testSetUserData($firstName, $nickName, $lastName, $email, $facebookId, $accessToken, $result)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $defaultAvatarPath= '/../web/images/default_avatar.png';
        $facebookProvider = new FacebookProvider($container, $kernelWebDir, $uploadDir, $defaultAvatarPath);
        $user = new User();
        $user->setFacebookId($facebookId);
        $response = new PathUserResponse();
        $responseArray = array(
                'first_name' => $firstName,
                'nickname'  => $nickName,
                'last_name'  => $lastName,
                'email'     => $email,
        );
        $response->setResponse($responseArray);
        $token = new OAuthToken($accessToken);
        $response->setOAuthToken($token);
        $filledUser = $facebookProvider->setUserData($user, $response);
        $this->assertEquals($filledUser->getFirstName(), $firstName);
        $this->assertEquals($filledUser->getMiddleName(), $nickName);
        $this->assertEquals($filledUser->getLastName(), $lastName);
        //$this->assertEquals($filledUser->getEmail(), $email); //because of the fake email usage
        $avatarPath = $filledUser->getAvatar()->getBinaryContent();
        $this->assertNotEmpty($avatarPath);
        $defaultAvatarId = $facebookProvider->getDefaultAvatar()->getId();
        if (!$result){
            $this->assertEquals($defaultAvatarId, $filledUser->getId());
        } else {
            $this->assertNotEquals($defaultAvatarId, $filledUser->getId());
        }
    }

    public function getUserCredentialsData()
    {
        return array(
            array('Chedream', '', 'Tester', 'chedreamtester@gmail.com', 1447616499, 'CAAVur8KihjEBAB62pZAn6dWTedr0Nd25OrAgER4oyUJYsq2wjdZCVZBkq0aISfWY61UHfK6VPIh63qXY69T3n8WSPkdWXV406qhBx5dWN6d9wWkxV8vS3J93c3S001mJqgD5KzqfCOsyWoDbWr0sbjIU3Q6ZBaY4w3avXmkd2qHhNUiigwMG', true),
            array('Chedream', '', 'Tester', 'chedreamtester@gmail.com', 1447616499, '12345', false),
        );
    }
}
