<?php

namespace Geekhub\DreamBundle\Service;

use Geekhub\DreamBundle\Model\DreamWithProgress;
use Geekhub\DreamBundle\Twig\DreamExtension;

class DreamsParsingService
{
    public function __construct(DreamExtension $count)
    {
        $this->count = $count;
    }

    public function getDreamsParsing($dreams){
        foreach ($dreams as $key => $dream) {
            $dreamWithProgress = new DreamWithProgress();

            $dreamWithProgress->setDream($dream);
            $dreamWithProgress->setDreamEquipmentProgress($this->count->showPercentOfCompletionEquipment($dream));
            $dreamWithProgress->setDreamFinancialProgress($this->count->showPercentOfCompletionFinancial($dream));
            $dreamWithProgress->setDreamWorkProgress($this->count->showPercentOfCompletionWork($dream));

            $dreamsAllWithProgress[$key] = $dreamWithProgress;
        }

        return $dreamsAllWithProgress;
    }
}
