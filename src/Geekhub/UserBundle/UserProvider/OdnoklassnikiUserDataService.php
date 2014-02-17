<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types;

class OdnoklassnikiUserDataService extends AbstractUserDataService
{
    protected $appKeys;
	
    public function setUserData(UserInterface $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();
        $user->setFirstName($responseArray['first_name']);
        $user->setMiddleName('');
        $user->setLastName($responseArray['last_name']);
        $user->setEmail('dumb@mail.ru'); //!error with same emails and different social networks
        $birthday=$responseArray['birthday'];
        $user->setBirthday(new \DateTime($birthday));

        $token = $response->getAccessToken();
        $tokenArray = str_split($token, rand(5, 10));
        $result = $this->doOdnoklassnikiApiRequest('photos.getUserPhotos', $token);
        $resultObj = json_decode($result);

        $profilePicture = null;
     
        if ((array_key_exists('photos',$resultObj)) &&($resultObj->photos[0]->standard_url)) {
            $profilePicture = $this->copyImgFromRemote($resultObj->photos[0]->standard_url, md5('ok'.$user->getOdnoklassnikiId()).'.jpg');
        }

        $user->setAvatar($profilePicture);
       
        return $user;
    }

        /**
     * @param string $method Method from Odnoklassniki REST API http://dev.odnoklassniki.ru/wiki/display/ok/Odnoklassniki+REST+API+ru
     * @param string $token Security token
     * @param array  $parameters Array parameters for current method
     */
    private function doOdnoklassnikiApiRequest($method, $token, $parameters = array())
    {
        $odnoklassniki_app_secret = $this->appKeys['odnoklassniki_app_secret'];
        $odnoklassniki_app_key = $this->appKeys['odnoklassniki_app_key'];

        $url = 'http://api.odnoklassniki.ru/fb.do?method='.$method;
        $sig = md5(
            'application_key=' . $odnoklassniki_app_key .
            'method=' . $method .
            md5($token . $odnoklassniki_app_secret)
        );
 
        $arrayParameters = array(
            'access_token' => $token,
            'application_key' => $odnoklassniki_app_key,
            'sig' => $sig,
        ); 

        $arrayParameters = array_merge($parameters, $arrayParameters);

        $url .= '&' . http_build_query($arrayParameters);
        return file_get_contents($url);
    }

    public function setAppKeys($appKeys)
    {
        $this->appKeys = $appKeys;
    }
}
