<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types;
use GuzzleHttp\Client;
use Geekhub\UserBundle\Model\VkontakteResponse;

class VkontakteUserDataService extends AbstractUserDataService
{
    protected $serializer;

    public function __construct($serializer)
    {
        $this->serializer=$serializer;
    }

    public function setUserData(UserInterface $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();
        $user->setFirstName($responseArray['response'][0]['first_name']);
        $user->setMiddleName($responseArray['response'][0]['nickname']);
        $user->setLastName($responseArray['response'][0]['last_name']);
        $user->setEmail('');
        //$user->setBirthday('0000-00-00');
        $profilePicture = null;
        if ($remoteImg = $this->callVkontakteUsersGet($user->GetVkontakteId(), $response->getAccessToken(), 'photo_big')) {
            $profilePicture = $this->copyImgFromRemote($remoteImg, md5('fb'.$user->GetVkontakteId()).'.jpg');
        }
        $user->setAvatar($profilePicture);
        $birthday = null;
        if ($userInfo = $this->callVkontakteUsersGet($user->GetVkontakteId(), $response->getAccessToken(), 'bdate')) {
            $birthday = $userInfo;
            // the date is DD.MM.YYYY or DD.MM if there is no year
            $birthdayArray=explode('.',$birthday);
            $birthdayDay=$birthdayArray[0];
            if (strlen($birthdayDay)<2)$birthdayDay='0'.$birthdayDay;
            $birthdayMonth=$birthdayArray[1];
            if (strlen($birthdayMonth)<2) {
                $birthdayMonth='0'.$birthdayMonth;
            }
            if (array_key_exists(2, $birthdayArray)) {
                $birthdayYear=$birthdayArray[2];
            }
            else {
                $birthdayYear='0000';
            }
            $birthday=$birthdayYear.'-'.$birthdayMonth.'-'.$birthdayDay;
        }
        $user->setBirthday(new \DateTime($birthday));
       
        return $user;
    }

    private function callVkontakteUsersGet($uid, $token, $field)
    {
        //$result = file_get_contents('https://api.vk.com/method/getProfiles?uid='.$uid.'&fields='.$field.'&access_token='.$token);
        $client = new Client(); 
        $response = $client->get('https://api.vk.com/method/getProfiles?uid='.$uid.'&fields='.$field.'&access_token='.$token);//, [//', [
        $responceBody = $response->getBody();
        //$result = json_decode($result, true);
        $result = $this->serializer->deserialize($responceBody, 'Geekhub\UserBundle\Model\VkontakteResponse', 'json');

        //if (isset($result['response'][0][$field])) {
        //    return $firstName = $result['response'][0][$field];
        //}
        if ($result) {
            return $firstName = $result->getResponse($field);
        }

        return null;
    }
}
