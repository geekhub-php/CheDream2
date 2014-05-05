<?php
/**
 * Created by PhpStorm.
 * File: DreamExtention.php
 * User: Yuriy Tarnavskiy
 * Date: 05.03.14
 * Time: 16:24
 */

namespace Application\Sonata\MediaBundle\Twig;

use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\MediaBundle\Provider\Pool;

class MediaExtension extends \Twig_Extension
{
    protected $mediaPool;

    public function __construct(Pool $pool)
    {
        $this->mediaPool = $pool;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mediaPath', array($this, 'mediaPath')),
            new \Twig_SimpleFilter('mediaYouTubeVideoId', array($this, 'mediaYouTubeVideoId')),
        );
    }

    public function mediaPath($media)
    {
        if ($media instanceof Media) {
            $provider = $this->mediaPool->getProvider($media->getProviderName());
            $format = $provider->getFormatName($media, 'reference');

            return $provider->generatePublicUrl($media, $format);
        } elseif (null == $media) {
            return 'bundles/applicationsonatamedia/img/default-no-image.png';
        }

        return $media;
    }

    public function mediaYouTubeVideoId($media)
    {
        if ($media instanceof Media) {

            return $media->getProviderReference();
        } else {
            return '#';
        }
    }

    public function getName()
    {
        return 'media_extension';
    }
}
