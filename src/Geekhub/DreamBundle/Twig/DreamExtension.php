<?php
/**
 * Created by PhpStorm.
 * File: DreamExtention.php
 * User: Yuriy Tarnavskiy
 * Date: 05.03.14
 * Time: 16:24
 */

namespace Geekhub\DreamBundle\Twig;

use Sonata\MediaBundle\Provider\Pool;

class DreamExtension extends \Twig_Extension
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
        $provider = $this->mediaPool->getProvider($media->getProviderName());
        $format = $provider->getFormatName($media, 'reference');

        return substr($provider->generatePublicUrl($media, $format), 3);
    }

    public function getName()
    {
        return 'dream_extension';
    }
}
