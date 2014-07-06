<?php

namespace Geekhub\UserBundle\Tests\UserProvider;

use Geekhub\UserBundle\Entity\User;
use Geekhub\UserBundle\UserProvider\OdnoklassnikiProvider;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;

class OdnoklassnikiProviderTest extends WebTestCase
{
    /**
     * @dataProvider getUserCredentialsData
     */
    public function testSetUserData($firstName, $nickName, $lastName, $birthday, $email, $odnoklassnikiId, $accessToken, $result)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $kernelWebDir = '/var/www/CheDream2/app';
        $uploadDir = '/upload/';
        $defaultAvatarPath= '/../web/images/default_avatar.png';
        $odnoklassnikiProvider = new OdnoklassnikiProvider($container, $kernelWebDir, $uploadDir, $defaultAvatarPath);
        $user = new User();
        $user->setOdnoklassnikiId($odnoklassnikiId);
        $response = new PathUserResponse();
        $responseArray = array(
                'first_name' => $firstName,
                'nickname'  => $nickName,
                'last_name'  => $lastName,
                'birthday'  => $birthday,
                'email'     => $email,
        );
        $response->setResponse($responseArray);
        $token = new OAuthToken($accessToken);
        $response->setOAuthToken($token);
        $filledUser = $odnoklassnikiProvider->setUserData($user, $response);
        $this->assertEquals($filledUser->getFirstName(), $firstName);
        $this->assertEquals($filledUser->getMiddleName(), $nickName);
        $this->assertEquals($filledUser->getLastName(), $lastName);
        //$this->assertEquals($filledUser->getEmail(), $email); //because of the fake email usage
        $avatarPath = $filledUser->getAvatar()->getBinaryContent();
        $this->assertNotEmpty($avatarPath);
        $defaultAvatarPath = $odnoklassnikiProvider->getDefaultAvatar()->getBinaryContent();
        if (!$result) {
            $this->assertEquals($defaultAvatarPath, $avatarPath);
        } else {
            $this->assertNotEquals($defaultAvatarPath, $avatarPath);
        }
    }

    public function getUserCredentialsData()
    {
        return array(
            //array('Ivan', '', 'Ivanov', '1987-05-03', 'chedreamtester@gmail.com', 147596068781, 'esipa.3061dvr7qgc02v5u3q5l2i5x3k4tc', true),// token expired ;(
            array('Ivan', '', 'Ivanov', '1987-05-03', 'chedreamtester@gmail.com', 147596068781, '12345', false),
        );
    }
}
