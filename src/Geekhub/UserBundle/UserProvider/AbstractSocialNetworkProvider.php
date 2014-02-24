<?php

namespace Geekhub\UserBundle\UserProvider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\DependencyInjection\Container;
use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\MediaBundle\Entity\MediaManager,
    Sonata\MediaBundle\Provider\ImageProvider;
use Geekhub\UserBundle\Entity\User;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractSocialNetworkProvider
{
    /** @var  string $kernelWebDir */
    protected $kernelWebDir;

    /** @var  string $kernelWebDir */
    protected $uploadDir;

    /** @var  Serializer $serializer */
    protected $serializer;

    /** @var  MediaManager $mediaManager */
    protected $mediaManager;

    /** @var  ImageProvider $mediaImageProvider */
    protected $mediaImageProvider;

    /** @var  Container $container */
    protected $container;

    public function __construct(Container $container, $kernelWebDir, $uploadDir)
    {
        $this->container          = $container;
        $this->serializer         = $container->get('jms_serializer');
        $this->mediaImageProvider = $container->get('sonata.media.provider.image');
        $this->kernelWebDir       = $kernelWebDir;
        $this->uploadDir          = $uploadDir;
    }

    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }


    public function getMediaFromRemoteImg($remoteImg, $localFileName)
    {
        $destination = $this->kernelWebDir.'/../web'.$this->uploadDir;
        $localImg = $destination.$localFileName;

        $filesystem = new Filesystem();
        $filesystem->copy($remoteImg, $localImg);

        $media = new Media;
        $media->setBinaryContent($localImg);
        $media->setProviderName('sonata.media.provider.image');
 
        $mediaManager = $this->container->get('sonata.media.manager.media');
        $mediaManager->save($media);

        return $media;
    }

    abstract function setUserData(User $user, UserResponseInterface $response);
}
