<?php

namespace Geekhub\UserBundle\Form;

use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Sonata\MediaBundle\Entity\MediaManager;
use Geekhub\UserBundle\Entity\User;

class FileMediaTransformer implements DataTransformerInterface
{

    protected $media;
    protected $mediaManager;
    protected $user;

    public function __construct(User $user, MediaManager $mediaManager)
    {
        $this->mediaManager = $mediaManager;
        $this->user = $user;
    }


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
            $this->mediaManager->save($media);
            $this->user->setAvatar($media);
            $mediaId = $this->media->getId();
            $oldMedia = $this->mediaManager->findOneBy(array('id' => $mediaId));
            $this->mediaManager->delete($oldMedia);

            return $media;
        } else {
            return $this->media;
        }
    }
}
