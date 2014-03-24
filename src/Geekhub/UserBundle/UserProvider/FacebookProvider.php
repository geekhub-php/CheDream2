<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Guzzle\Http\Client;
use Geekhub\UserBundle\Entity\User;

class FacebookProvider extends AbstractSocialNetworkProvider
{
    public function setUserData(User $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();

        $user->setFirstName($responseArray['first_name']);
        $user->setLastName($responseArray['last_name']);
        $user->setEmail($responseArray['email']);

        $remoteImg = 'http://graph.facebook.com/'.$user->getFacebookId().'/picture?width=200&height=200';
        $profilePicture = $this->getMediaFromRemoteImg($remoteImg, md5('fb'.$user->getFacebookId()).'.jpg');

        $user->setAvatar($profilePicture);
        $userInfo = $this->getFacebookUserInfo($response->getAccessToken());

        if ($userInfo->getBirthday()) {
            $birthday = \DateTime::createFromFormat('m/d/Y', $userInfo->getBirthday());
            $user->setBirthday($birthday);
        }

        return $user;
    }

    private function getFacebookUserInfo($token)
    {
        $client = new Client();

        $response = $client->get('https://graph.facebook.com/me?access_token='.$token);
        $responseBody = $response->getResponse();

        return $this->serializer->deserialize($responseBody, 'Geekhub\UserBundle\Model\FacebookUserInfoResponse', 'json');
    }
}
