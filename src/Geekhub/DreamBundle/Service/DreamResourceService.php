<?php

namespace Geekhub\DreamBundle\Service;

class DreamResourceService
{

    public function getResource($financialContributions, $equipmentContributions, $workContributions)
    {
        $this->financialContributions = $financialContributions;
        $this->equipmentContributions = $equipmentContributions;
        $this->workContributions = $workContributions;

        return $this->getFinancialProgress();

    }

    private function getFinancialProgress()
    {
        foreach ($this->financialContributions as $key => $financialContributionsData) {
            $user[$key] = $financialContributionsData->getUser();
            $financialContributionsHidden[$key] = $financialContributionsData->getQuantity();
            $financialResourceHidden[$key] = $financialContributionsData->getFinancialResource()->getQuantity();

            $progress[$key] = round(($financialContributionsHidden[$key]/$financialResourceHidden[$key])*100);
        }

        $progressAll = array_sum($progress);

        return round((count($user)/$progressAll)*100);
    }

    private function getEquipmentProgress()
    {
        foreach ($this->equipmentContributions as $key => $equipmentContributionsData) {
            $user[$key] = $equipmentContributionsData->getUser();
            $equipmentContributionsHidden[$key] = $equipmentContributionsData->getQuantity();
            $financialResourceHidden[$key] = $equipmentContributionsData->getEquipmentResource()->getQuantity();

            $progress[$key] = round(($equipmentContributionsHidden[$key]/$financialResourceHidden[$key])*100);
        }

        $progressAll = array_sum($progress);

        return $progress;
    }

    private function getWorkProgress()
    {
        foreach ($this->workContributions as $key => $workContributionsData) {
            $user[$key] = $workContributionsData->getUser();
            $workContributionsHidden[$key] = $workContributionsData->getQuantity();
            $financialResourceHidden[$key] = $workContributionsData->getEquipmentResource()->getQuantity();

            $progress[$key] = round(($workContributionsHidden[$key]/$financialResourceHidden[$key])*100);
        }

        $progressAll = array_sum($progress);

        return $progress;
    }
}
