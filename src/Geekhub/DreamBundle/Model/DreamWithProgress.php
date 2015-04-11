<?php

namespace Geekhub\DreamBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class DreamWithProgress
 * @package Geekhub\DreamBundle\Model
 * @ExclusionPolicy("all")
 */
class DreamWithProgress
{
    /**
     *
     * @Type("Geekhub\DreamBundle\Entity\Dream")
     * @Expose
     */
    protected $dream;

    /**
     * @var integer
     *
     * @Expose
     */
    protected $dreamEquipmentProgress;

    /**
     * @var integer
     *
     * @Expose
     */
    protected $dreamFinancialProgress;

    /**
     * @var integer
     *
     * @Expose
     */
    protected $dreamWorkProgress;

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
        return $this->dreamEquipmentProgress;
    }

    /**
     * @param mixed $dreamEquipmentProgress
     */
    public function setDreamEquipmentProgress($dreamEquipmentProgress)
    {
        $this->dreamEquipmentProgress = $dreamEquipmentProgress;
    }

    /**
     * @return mixed
     */
    public function getDreamFinancialProgress()
    {
        return $this->dreamFinancialProgress;
    }

    /**
     * @param mixed $dreamFinancialProgress
     */
    public function setDreamFinancialProgress($dreamFinancialProgress)
    {
        $this->dreamFinancialProgress = $dreamFinancialProgress;
    }

    /**
     * @return mixed
     */
    public function getDreamWorkProgress()
    {
        return $this->dreamWorkProgress;
    }

    /**
     * @param mixed $dreamWorkProgress
     */
    public function setDreamWorkProgress($dreamWorkProgress)
    {
        $this->dreamWorkProgress = $dreamWorkProgress;
    }

}
