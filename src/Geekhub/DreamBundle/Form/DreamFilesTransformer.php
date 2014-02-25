<?php
/**
 * Created by PhpStorm.
 * File: DreamFilesTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 24.02.14
 * Time: 23:09
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Geekhub\DreamBundle\Entity\Dream;
use Sonata\MediaBundle\Entity\MediaManager;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DreamFilesTransformer implements DataTransformerInterface
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

    public function reverseTransform($filesString)
    {
        if (null === $filesString) {
            return;
        }

        $fs = new Filesystem();

        $filesSrcArray = explode (',', $filesString);
        array_shift($filesSrcArray);

        foreach($filesSrcArray as $fileSrc)
        {
            $media = new Media();

            $file = new File($fileSrc);

//            var_dump($file->getBasename(), $file->getPath(), $file->getRealPath()); exit;
            $media->setBinaryContent($file->getRealPath());
//            $media->setBinaryContent($pictureSrc);
            $media->setProviderName('sonata.media.provider.file');
            $media->setContext('files');
            $this->mediaManager->save($media);
            $this->dream->addMedia($media);

//            try {
//                $fs->remove($file);
//            } catch (IOExceptionInterface $e) {
//            }
        }

        return;
    }
}