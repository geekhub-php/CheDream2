<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types;
use GuzzleHttp\Client;
use Geekhub\UserBundle\Model\VkontakteResponse;


class FacebookUserDataService extends AbstractUserDataService
{
    protected $serializer;

    public function __construct($serializer)
    {
        $this->serializer=$serializer;
    }
	
    public function setUserData(UserInterface $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();
        $user->setFirstName($responseArray['first_name']);
        $user->setMiddleName('');
        $user->setLastName($responseArray['last_name']);
        $user->setEmail($responseArray['email']);
        //  $user->setBirthday(new DateTimeType('1901-01-01'));
        $remoteImg = 'http://graph.facebook.com/'.$user->getFacebookId().'/picture?width=200&height=200';
        $profilePicture = $this->copyImgFromRemote($remoteImg, md5('fb'.$user->getFacebookId()).'.jpg');
        $user->setAvatar($profilePicture);
        $userInfo = $this->getFacebookUserInfo($response->getAccessToken());
        if ($birthday=$userInfo->getBirthday()) {
            $birthdayMonth=substr($birthday,0,2);
            $birthdayDay=substr($birthday,3,2);
            $birthdayYear=substr($birthday,6,4);
            $birthday=$birthdayYear.'-'.$birthdayMonth.'-'.$birthdayDay;
            $user->setBirthday(new \DateTime($birthday));
        }

        return $user;
    }

    private function getFacebookUserInfo($token)
    {
        //$result = file_get_contents('https://graph.facebook.com/me?access_token='.$token);
        $client = new Client();
        $response = $client->get('https://graph.facebook.com/me?access_token='.$token);
        $responceBody = $response->getBody();
        //$result = json_decode($result, true);
        echo $responceBody;
        $result = $this->serializer->deserialize($responceBody, 'Geekhub\UserBundle\Model\FacebookUserInfoResponse', 'json');
        var_dump($result);
        //exit;

        return $result;
    }
}
