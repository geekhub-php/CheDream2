<?php
/**
 * Created by PhpStorm.
 * File: WorkTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 17:16
 */

namespace Geekhub\DreamBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\Form\DataTransformerInterface;

class WorkTransformer implements DataTransformerInterface
{
    protected $dream;

    public function __construct(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function transform($t)
    {
        $resources = $this->dream->getDreamResources();
        $workResources = new ArrayCollection;

        foreach($resources as $resource) {
            switch ($resource->getType()) {
                case $resource::FINANCIAL:
                    break;
                case $resource::EQUIPMENT:
                    break;
                case $resource::WORK:
                    $workResources->add($resource);
                    break;
            }
        }

        return $workResources;
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
