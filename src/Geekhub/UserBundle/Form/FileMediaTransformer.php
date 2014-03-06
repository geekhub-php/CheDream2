<?php

namespace Geekhub\UserBundle\Form;

use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileMediaTransformer implements DataTransformerInterface
{

    protected $media;

    public function transform($media)
    {
        $this->media = $media;
        return ;
    }

    public function reverseTransform($file)
    {
        if ($file) {
            $media = new Media();
            $media->setBinaryContent($file);//'/var/www/CheDream2/web/upload/dream/image/cache/dream_poster/upload/tmp/poster/'.$fileName);
            $media->setProviderName('sonata.media.provider.image');
            $media->setContext('avatar');
            return $media;
        }
        else {
            return $this->media;
        }

    }
}
