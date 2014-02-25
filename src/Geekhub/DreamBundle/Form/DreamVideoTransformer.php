<?php
/**
 * Created by PhpStorm.
 * File: DreamVideoTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 24.02.14
 * Time: 23:17
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Geekhub\DreamBundle\Entity\Dream;
use Sonata\MediaBundle\Entity\MediaManager;
use Application\Sonata\MediaBundle\Entity\Media;

class DreamVideoTransformer implements DataTransformerInterface
{
    protected $dream;
    protected $mediaManager;

    public function __construct(Dream $dream, MediaManager $mediaManager)
    {
        $this->dream = $dream;
        $this->mediaManager = $mediaManager;
    }

    public function transform($t)
    {
        return;
    }

    public function reverseTransform($videosString)
    {
        if (null === $videosString) {
            return;
        }

        $videosSrcArray = explode (',', $videosString);
        array_shift($videosSrcArray);

        foreach($videosSrcArray as $videoSrc)
        {
            $media = new Media();
            $media->setBinaryContent($videoSrc);
            $media->setProviderName('sonata.media.provider.youtube');
            $media->setContext('video');
            $this->mediaManager->save($media);
            $this->dream->addMedia($media);
        }

        return;
    }
}