<?php

namespace Geekhub\TagBundle;

use FPN\TagBundle\Entity\TagManager as BaseTagManager;
use DoctrineExtensions\Taggable\Taggable;

class TagManager extends BaseTagManager
{
    public function addTagsToEntity(Taggable $entity)
    {
        $tagsObjArray = self::loadOrCreateTags($entity->getTags());
        $entity->setTags(null);
        parent::addTags($tagsObjArray, $entity);
    }
}