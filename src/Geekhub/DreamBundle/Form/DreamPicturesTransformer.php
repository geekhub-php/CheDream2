<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 23.02.14
 * Time: 22:18
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Geekhub\DreamBundle\Entity\Dream;
use Sonata\MediaBundle\Entity\MediaManager;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;

class DreamPicturesTransformer implements DataTransformerInterface
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

    public function reverseTransform($picturesString)
    {
        if (null === $picturesString) {
            return;
        }

        $picturesSrcArray = explode (',', $picturesString);
        array_shift($picturesSrcArray);

//        var_dump($picturesSrcArray);
//        foreach($picturesSrcArray as $pictureSrc)
//        {
//            $media = new Media();
//
//            $file = new File($pictureSrc);
//
////            var_dump($file->getBasename(), $file->getPath(), $file->getRealPath()); exit;
//            $media->setBinaryContent($file->getRealPath());
//            $media->setBinaryContent($pictureSrc);
//            $media->setProviderName('sonata.media.provider.image');
//
//            $this->mediaManager->save($media);
//
//            $this->dream->addMedia($media);
//            unlink($file);
//        }

        return $picturesSrcArray;
    }
}