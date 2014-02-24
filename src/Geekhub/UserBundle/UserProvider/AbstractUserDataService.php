<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\DependencyInjection\Container;
use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\MediaBundle\Entity\MediaManager,
    Sonata\MediaBundle\Provider\ImageProvider;
use Geekhub\UserBundle\Entity\User;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractUserDataService
{
    protected $kernelWebDir;

    protected $uploadDir = '/upload/';

    /** @var Serializer $serializer */
    protected $serializer;

    /** @var MediaManager $mediaManager */
    protected $mediaManager;

    /** @var ImageProvider $mediaImageProvider */
    protected $mediaImageProvider;

    /** @var Container $container */
    protected $container;

    public function __construct(Container $container, $kernelWebDir, $uploadDir)
    {
        $this->container          = $container;
        $this->serializer         = $container->get('jms_serializer');
        $this->mediaManager       = 0;//$container->get('sonata.media.manager.media');
        $this->mediaImageProvider = $container->get('sonata.media.provider.image');
        $this->kernelWebDir       = $kernelWebDir;
        $this->uploadDir            = $uploadDir;
    }

    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }


    public function copyImgFromRemote($remoteImg, $localFileName)
    {
        $filesystem = new Filesystem();
//        $content = file_get_contents($remoteImg);

        $destination = $this->kernelWebDir.'/../web'.$this->uploadDir;
//        var_dump($destination);

//        $filesystem->mkdir($destination);

        $localImg = $destination.$localFileName;

        $filesystem->copy($remoteImg, $localImg);

//        $fp = fopen($localImg, "w");
//        fwrite($fp, $content);
//        fclose($fp);

        // return $this->uploadDir.$localFileName; // version without SonataMediaBundle (file path)
        $media = new Media;
        $media->setBinaryContent($localImg);
        $media->setProviderName('sonata.media.provider.image');
 
        $mediaManager = $this->container->get('sonata.media.manager.media'); // don't need. Injected
        $mediaManager->save($media); // !!! error! Doesnt work 
 var_dump($media); exit;
        return $media;
    }

    abstract function setUserData(User $user, UserResponseInterface $response);
}
