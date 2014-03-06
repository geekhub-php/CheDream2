<?php
/**
 * Created by PhpStorm.
 * File: TagTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 11:01
 */

namespace Geekhub\DreamBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    public function transform($tags)
    {
        if ($tags->isEmpty()) {
            return "";
        }

        return implode(',', $tags->toArray());
    }

    public function reverseTransform($tagsString)
    {
        if (null === $tagsString) {
            return;
        }

        $tagsArray = explode (',', $tagsString);
        $tagObjects = array();

        foreach ($tagsArray as $tagTitle) {
            $tagObjects[] = $tagTitle;
        }

        return $tagObjects;
    }
}
