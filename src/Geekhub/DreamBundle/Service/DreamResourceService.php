<?php

namespace Geekhub\DreamBundle\Service;

class DreamResourceService
{

    public function getResource($financialContributions)
    {
        $this->financialContributions = $financialContributions;

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
}
//
//[
//  {
//      "created_at": "2015-03-23T20:20:48+0200",
//    "quantity": 10,
//    "dream": [],
//    "hidden_contributor": true,
//    "id": 12,
//    "financial_resource": {
//      "created_at": "2015-03-23T20:20:48+0200",
//      "quantity": 50,
//      "dream": [],
//      "title": "фінанс3",
//      "id": 35
//    }
//  },
//  {
//      "created_at": "2015-03-23T20:20:48+0200",
//    "quantity": 50,
//    "dream": [],
//    "hidden_contributor": true,
//    "id": 13,
//    "financial_resource": {
//      "created_at": "2015-03-23T20:20:48+0200",
//      "quantity": 50,
//      "dream": [],
//      "title": "фінанс3",
//      "id": 35
//    }
//  },
//  {
//      "created_at": "2015-03-23T20:20:48+0200",
//    "quantity": 50,
//    "dream": [],
//    "hidden_contributor": true,
//    "id": 14,
//    "financial_resource": {
//      "created_at": "2015-03-23T20:20:48+0200",
//      "quantity": 50,
//      "dream": [],
//      "title": "фінанс3",
//      "id": 35
//    }
//  }
//]