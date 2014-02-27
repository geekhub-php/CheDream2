<?php
/**
 * Created by PhpStorm.
 * File: EquipmentTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 17:12
 */

namespace Geekhub\DreamBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\Form\DataTransformerInterface;

class EquipmentTransformer implements DataTransformerInterface
{
    protected $dream;

    public function __construct(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function transform($t)
    {
        $resources = $this->dream->getDreamResources();
        $equipmentResources = new ArrayCollection;

        foreach ($resources as $resource) {
            switch ($resource->getType()) {
                case $resource::FINANCIAL:
                    break;
                case $resource::EQUIPMENT:
                    $equipmentResources->add($resource);
                    break;
                case $resource::WORK:
                    break;
            }
        }

        return $equipmentResources;
    }

    public function reverseTransform($equipmentArrayCollection)
    {
        foreach ($equipmentArrayCollection as $equipment) {
            $equipment->setDream($this->dream);
            $this->dream->addDreamResource($equipment);
        }

        return;
    }
}
