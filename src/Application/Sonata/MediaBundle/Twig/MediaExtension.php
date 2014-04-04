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
        );
    }

    public function mediaPath($media)
    {
        if ($media instanceof Media) {
            $provider = $this->mediaPool->getProvider($media->getProviderName());
            $format = $provider->getFormatName($media, 'reference');

            return substr($provider->generatePublicUrl($media, $format), 0);
        } elseif (null == $media) {
            return 'bundles/applicationsonatamedia/img/default-no-image.png';
        }

        return $media;
    }

    public function getName()
    {
        return 'media_extension';
    }
}
