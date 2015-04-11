<?php

namespace Geekhub\DreamBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class DreamResponse
 * @package Geekhub\DreamBundle\Model
 * @ExclusionPolicy("all")
 */
class DreamResponse
{
    /**
     *
     * @Type("Geekhub\DreamBundle\Entity\Dream")
     * @Expose
     */
    protected $dream;

    /**
     * @Expose
     */
    protected  $dream_equipment_progress;

    /**
     * @Expose
     */
    protected  $dream_financial_progress;

    /**
     * @Expose
     */
    protected  $dream_work_progress;

    /**
     * @return mixed
     */
    public function getDream()
    {
        return $this->dream;
    }

    /**
     * @param mixed $dream
     */
    public function setDream($dream)
    {
        $this->dream = $dream;
    }

    /**
     * @return mixed
     */
    public function getDreamEquipmentProgress()
    {
        return $this->dream_equipment_progress;
    }

    /**
     * @param mixed $dream_equipment_progress
     */
    public function setDreamEquipmentProgress($dream_equipment_progress)
    {
        $this->dream_equipment_progress = $dream_equipment_progress;
    }

    /**
     * @return mixed
     */
    public function getDreamFinancialProgress()
    {
        return $this->dream_financial_progress;
    }

    /**
     * @param mixed $dream_financial_progress
     */
    public function setDreamFinancialProgress($dream_financial_progress)
    {
        $this->dream_financial_progress = $dream_financial_progress;
    }

    /**
     * @return mixed
     */
    public function getDreamWorkProgress()
    {
        return $this->dream_work_progress;
    }

    /**
     * @param mixed $dream_work_progress
     */
    public function setDreamWorkProgress($dream_work_progress)
    {
        $this->dream_work_progress = $dream_work_progress;
    }

}
