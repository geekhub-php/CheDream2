<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\DependencyInjection\Container;
use JMS\Serializer\Serializer;
use Sonata\MediaBundle\Entity\MediaManager,
    Sonata\MediaBundle\Provider\ImageProvider;
use Geekhub\UserBundle\Entity\User;

abstract class AbstractUserDataService
{
    protected $kernelWebDir;

    protected $imgPath = '/uploads/';

    /** @var Serializer $serializer */
    protected $serializer;

    /** @var MediaManager $mediaManager */
    protected $mediaManager;

    /** @var ImageProvider $mediaImageProvider */
    protected $mediaImageProvider;

    public function __construct(Container $container, $kernelWebDir, $imgPath)
    {
        $this->serializer         = $container->get('jms_serializer');
        $this->mediaManager       = $container->get('sonata.media.manager.media');
        $this->mediaImageProvider = $container->get('sonata.media.provider.image');
        $this->kernelWebDir       = $kernelWebDir;
        $this->imgPath            = $imgPath;
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

    abstract function setUserData(User $user, UserResponseInterface $response);
}
