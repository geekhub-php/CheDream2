<?php

namespace Geekhub\DreamBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentResource;
use Geekhub\DreamBundle\Entity\FinancialResource;
use Geekhub\DreamBundle\Entity\WorkResource;

class DreamResourceService
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function showPercentOfCompletionFinancial(Dream $dream)
    {
        if (count($dream->getDreamFinancialResources()) == 0) {
            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamFinancialResources()->map($this->getQuantity())->toArray();
        $financialResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamFinancialContributions()->map($this->getQuantity())->toArray();
        $financialContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($financialResourcesSum, $financialContributionsSum);
    }

    public function showPercentOfCompletionEquipment(Dream $dream)
    {
        if (count($dream->getDreamEquipmentResources()) == 0) {
            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamEquipmentResources()->map($this->getQuantity())->toArray();
        $equipmentResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamEquipmentContributions()->map($this->getQuantity())->toArray();
        $equipmentContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($equipmentResourcesSum, $equipmentContributionsSum);
    }

    public function showPercentOfCompletionWork(Dream $dream)
    {
        if (count($dream->getDreamWorkResources()) == 0) {
            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamWorkResources()->map($this->getQuantity())->toArray();
        $workResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamWorkContributions()->map($this->getQuantity())->toArray();
        $workContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($workResourcesSum, $workContributionsSum);
    }

}
