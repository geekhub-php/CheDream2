<?php
/**
 * Created by PhpStorm.
 * File: WorkTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 17:16
 */

namespace Geekhub\DreamBundle\Form;

use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\Form\DataTransformerInterface;

class WorkTransformer implements DataTransformerInterface
{
    protected $dream;

    public function __construct(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function transform($tags)
    {
        return null;
    }

    public function reverseTransform($workArrayCollection)
    {
        foreach($workArrayCollection as $work)
        {
            $work->setDream($this->dream);
            $this->dream->addDreamResource($work);
        }

        return;
    }
}
