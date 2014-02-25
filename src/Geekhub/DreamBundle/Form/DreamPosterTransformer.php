<?php
/**
 * Created by PhpStorm.
 * File: DreamPosterTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 24.02.14
 * Time: 22:48
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Geekhub\DreamBundle\Entity\Dream;
use Sonata\MediaBundle\Entity\MediaManager;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DreamPosterTransformer implements DataTransformerInterface
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

        $fs = new Filesystem();

        $media = new Media();

        $file = new File($picturesString);

//        var_dump($file->getBasename(), $file->getPath(), $file->getRealPath()); exit;
        $media->setBinaryContent($file->getRealPath());
//        $media->setBinaryContent($picturesString);
        $media->setProviderName('sonata.media.provider.image');
        $media->setContext('poster');
        $this->mediaManager->save($media);
        $this->dream->setMediaPoster($media);

        try {
            $fs->remove($file);
        } catch (IOExceptionInterface $e) {
        }

        return;
    }
}