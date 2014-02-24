<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Doctrine\DBAL\Types;
use GuzzleHttp\Client;
use Geekhub\UserBundle\Entity\User;

class OdnoklassnikiUserDataService extends AbstractUserDataService
{
    protected $appKeys;

    public function setUserData(User $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();

        $user->setFirstName($responseArray['first_name']);
        $user->setMiddleName('');
        $user->setLastName($responseArray['last_name']);
        $user->setEmail($user->getOdnoklassnikiId().'@odnoklassniki.ru');
        $user->setBirthday(new \DateTime($responseArray['birthday']));

        $token = $response->getAccessToken();
        $photoUrl = $this->doOdnoklassnikiApiRequest('photos.getUserPhotos', $token);

        if ($photoUrl) {
            $profilePicture = $this->getMediaFromRemoteImg($photoUrl, md5('ok'.$user->getOdnoklassnikiId()).'.jpg');
            $user->setAvatar($profilePicture);
        }

        return $user;
    }

        /**
     * @param string $method Method from Odnoklassniki REST API http://dev.odnoklassniki.ru/wiki/display/ok/Odnoklassniki+REST+API+ru
     * @param string $token Security token
     * @param array  $parameters Array parameters for current method
     */
    private function doOdnoklassnikiApiRequest($method, $token, $parameters = array())
    {
        $odnoklassnikiAppSecret = $this->appKeys['odnoklassniki_app_secret'];
        $odnoklassnikiAppKey = $this->appKeys['odnoklassniki_app_key'];

        $url = 'http://api.odnoklassniki.ru/fb.do?method='.$method;
        $sig = md5(
            'application_key=' . $odnoklassnikiAppKey .
            'method=' . $method .
            md5($token . $odnoklassnikiAppSecret)
        );
        $arrayParameters = array(
            'access_token' => $token,
            'application_key' => $odnoklassnikiAppKey,
            'sig' => $sig,
        ); 

        $arrayParameters = array_merge($parameters, $arrayParameters);

        $url .= '&' . http_build_query($arrayParameters);

        $client = new Client();
        $response = $client->get($url);
        $responseBody = $response->getBody();

        $resultObj = $this->serializer->deserialize($responseBody, 'Geekhub\UserBundle\Model\OdnoklassnikiPhotoResponse', 'json');

        return $resultObj->getPhoto();
    }

    public function setAppKeys($appKeys)
    {
        $this->appKeys = $appKeys;
    }
}
