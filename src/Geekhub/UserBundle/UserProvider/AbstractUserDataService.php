<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Application\Sonata\MediaBundle\Entity\Media;

abstract class AbstractUserDataService
{
    protected $kernelWebDir;

    protected $imgPath = '/upload/';

    protected $mediaManager;


    public function setKernelWebDir($kernelWebDir)
    {
        $this->kernelWebDir = $kernelWebDir;
    }

    public function setImgPath($imgPath)
    {
        $this->imgPath = $imgPath;
    }

    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }


    public function copyImgFromRemote($remoteImg, $localFileName)
    {
        $media = new Media;
        $media->setBinaryContent($remoteImg);
        $media->setProviderName('sonata.media.provider.image');
 
        //$mediaManager = $this->get('sonata.media.manager.media'); // don't need. Injected
        //$this->mediaManager->save($media); // !!! error! Doesnt work 
 
        return $media;
        /*
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
        */
    }

    abstract function setUserData(UserInterface $user, UserResponseInterface $response);
}
