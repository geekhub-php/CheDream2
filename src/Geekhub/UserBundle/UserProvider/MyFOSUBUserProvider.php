<?php

namespace Geekhub\UserBundle\UserProvider;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider as BaseOAuthUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Doctrine\DBAL\Types;
 
class MyFOSUBUserProvider extends BaseClass implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    protected $kernelWebDir;

    protected $imgPath = '/uploads/';

    protected $appKeys;
 
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
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        //var_dump("in loadUserByOAuthUserResponse");
        //var_dump($response);

        $username = $response->getUsername();

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setterId = $setter.'Id';
            $setterToken = $setter.'AccessToken';
            $setterUser = $setter.'User';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setterId($username);
            $user->$setterToken($response->getAccessToken());
            // I use different setters setters for each type of oath providers:
            // for example setVkontakteUser(...),  setFacebookUser(...)
            // the actual name of setter is in the variable $setterUser.
            $user = $this->$setterUser($user, $response);
            $user->setUsername($username);
            //$user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return $user;
        }
 
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setter($response->getAccessToken());
 
        return $user;
    }

    private function setFacebookUser($user, $response)
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
        if (array_key_exists('birthday',$userInfo)) {
            $birthday=$userInfo['birthday'];
            $birthdayMonth=substr($birthday,0,2);
            $birthdayDay=substr($birthday,3,2);
            $birthdayYear=substr($birthday,6,4);
            $birthday=$birthdayYear.'-'.$birthdayMonth.'-'.$birthdayDay;
            $user->setBirthday(new \DateTime($birthday));
        }

        return $user;
    }

    private function setVkontakteUser(UserInterface $user, UserResponseInterface $response)
    {
        $responseArray = $response->getResponse();
        $user->setFirstName($responseArray['response'][0]['first_name']);
        $user->setMiddleName($responseArray['response'][0]['nickname']);
        $user->setLastName($responseArray['response'][0]['last_name']);
        $user->setEmail('');
        //$user->setBirthday('0000-00-00');
        $profilePicture = null;
        if ($remoteImg = $this->callVkontakteUsersGet($user->GetVkontakteId(), $user->getVkontakteAccessToken(), 'photo_big')) {
            $profilePicture = $this->copyImgFromRemote($remoteImg, md5('fb'.$user->GetVkontakteId()).'.jpg');
        }
        $user->setAvatar($profilePicture);
        $birthday = null;
        if ($userInfo = $this->callVkontakteUsersGet($user->GetVkontakteId(), $user->getVkontakteAccessToken(), 'bdate')) {
            $birthday = $userInfo;
            // the date is DD.MM.YYYY or DD.MM if there is no year
            $birthdayArray=explode('.',$birthday);
            $birthdayDay=$birthdayArray[0];
            if (strlen($birthdayDay)<2)$birthdayDay='0'.$birthdayDay;
            $birthdayMonth=$birthdayArray[1];
            if (strlen($birthdayMonth)<2)$birthdayMonth='0'.$birthdayMonth;
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

    private function setOdnoklassnikiUser(UserInterface $user, UserResponseInterface $response)
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

    private function copyImgFromRemote($remoteImg, $localFileName)
    {
        $content = file_get_contents($remoteImg);
        $destination = $this->kernelWebDir.'/../web'.$this->imgPath;

        if (!is_dir($destination)) {
            mkdir($destination);
        }

        $localImg = $destination.$localFileName;

        $fp = fopen($localImg, "w");
        fwrite($fp, $content);
        fclose($fp);

        return $this->imgPath.$localFileName;
    }

        /**
     * Wrapper for Vk api - http://vk.com/developers.php?oid=-1&p=users.get
     *
     * @string $uid
     * @string $token
     * @string $field
     * @return string or null
     */
    private function callVkontakteUsersGet($uid, $token, $field)
    {
        $result = file_get_contents('https://api.vk.com/method/getProfiles?uid='.$uid.'&fields='.$field.'&access_token='.$token);
        $result = json_decode($result, true);

        if (isset($result['response'][0][$field])) {
            return $firstName = $result['response'][0][$field];
        }

        return null;
    }

    private function getFacebookUserInfo($token)
    {
        $result = file_get_contents('https://graph.facebook.com/me?access_token='.$token);
        $result = json_decode($result, true);

        return $result;
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

    public function setKernelWebDir($kernelWebDir)
    {
        $this->kernelWebDir = $kernelWebDir;
    }

    public function setAppKeys($appKeys)
    {
        $this->appKeys = $appKeys;
    }
}