<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractUserDataService
{
    protected $kernelWebDir;

    protected $imgPath = '/uploads/';


    public function setKernelWebDir($kernelWebDir)
    {
        $this->kernelWebDir = $kernelWebDir;
    }

    public function copyImgFromRemote($remoteImg, $localFileName)
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

    abstract function setUserData(UserInterface $user, UserResponseInterface $response);
}
