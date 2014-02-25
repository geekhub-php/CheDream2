<?php
/**
 * Created by PhpStorm.
 * File: FinancialTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 16:05
 */

namespace Geekhub\DreamBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\Form\DataTransformerInterface;

class FinancialTransformer implements DataTransformerInterface
{
    protected $dream;

    public function __construct(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function transform($t)
    {
        $resources = $this->dream->getDreamResources();
        $financeResources = new ArrayCollection;

        foreach ($resources as $resource) {
            switch ($resource->getType()) {
                case $resource::FINANCIAL:
                    $financeResources->add($resource);
                    break;
                case $resource::EQUIPMENT:
                    break;
                case $resource::WORK:
                    break;
            }
        }

        return $financeResources;
    }

    public function reverseTransform($financialArrayCollection)
    {
        foreach ($financialArrayCollection as $finance) {
            $finance->setDream($this->dream);
            $this->dream->addDreamResource($finance);
        }

        return;
    }
}
