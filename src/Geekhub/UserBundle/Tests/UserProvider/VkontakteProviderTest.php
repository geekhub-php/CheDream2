<?php

namespace Geekhub\UserBundle\Tests\UserProvider;

use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\VkontakteProvider;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class VkontakteProviderTest extends WebTestCase
{
    /**
     * @dataProvider getUserCredentialsData
     */
    public function testSetUserData($firstName, $nickName, $lastName, $email, $vkontakteId, $accessToken, $result)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $defaultAvatarPath= '/../web/images/default_avatar.png';
        $vkontakteProvider = new VkontakteProvider($container, $kernelWebDir, $uploadDir, $defaultAvatarPath);
        $user = new User();
        $user->setVkontakteId($vkontakteId);
        $response = new PathUserResponse();
        $responseArray = array(
            'response'=> array(
                0 => array(
                    'first_name' => $firstName,
                    'nickname'  => $nickName,
                    'last_name'  => $lastName,
                    'email'     => $email,
                )
            )
        );
        $response->setResponse($responseArray);
        $token = new OAuthToken($accessToken);
        $response->setOAuthToken($token);
        $filledUser = $vkontakteProvider->setUserData($user, $response);
        $this->assertEquals($filledUser->getFirstName(), $firstName);
        $this->assertEquals($filledUser->getMiddleName(), $nickName);
        $this->assertEquals($filledUser->getLastName(), $lastName);
        //$this->assertEquals($filledUser->getEmail(), $email); //because of the fake email usage
        $avatarPath = $filledUser->getAvatar()->getBinaryContent();
        $this->assertNotEmpty($avatarPath);
        $defaultAvatarPath = $vkontakteProvider->getDefaultAvatar()->getBinaryContent();
        if (!$result) {
            $this->assertEquals($defaultAvatarPath, $avatarPath);
        } else {
            $this->assertNotEquals($defaultAvatarPath, $avatarPath);
        }
    }

    public function getUserCredentialsData()
    {
        return array(
            //array('Ivan', '', 'Ivanov', 'chedreamtester@gmail.com', 251113893, '7d7543e1d8eab2f47b3bbf064802e451d85f93508e380358d94c41fd6e8c9590924f8ceb33b7cead130a4', true), // token expired ;(
            array('Ivan', '', 'Ivanov', 'chedreamtester@gmail.com', 251113893, '12345', false),
        );
    }
}
