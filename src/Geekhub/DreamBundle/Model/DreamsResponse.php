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
class DreamsResponse extends AbstractPagination
{
    /**
     * @var Array[]
     *
     * @Type("array<Geekhub\DreamBundle\Entity\Dream>")
     * @Expose
     */
    protected $dreams;

    /**
     * @var Array[]
     *
     * @Type("array<Geekhub\DreamBundle\Entity\EquipmentResource>")
     * @Expose
     */
    protected  $dream_equipment_progress;

    /**
     * @var Array[]
     *
     * @Type("array<Geekhub\DreamBundle\Entity\FinancialResource>")
     * @Expose
     */
    protected  $dream_financial_progress;

    /**
     * @var Array[]
     *
     * @Type("array<Geekhub\DreamBundle\Entity\WorkResource>")
     * @Expose
     */
    protected  $dream_work_progress;

    /**
     * @param mixed $dreams
     */
    public function setDreams($dreams)
    {
        $this->dreams = $dreams;
    }
    /**
     * @return mixed
     */
    public function getDreams()
    {
        return $this->dreams;
    }

    /**
     * @param mixed $selfPage
     */
    public function setSelfPage($selfPage)
    {
        $this->selfPage = $selfPage;
    }

    /**
     * @return mixed
     */
    public function getSelfPage()
    {
        return $this->selfPage;
    }

    /**
     * @param mixed $nextPage
     */
    public function setNextPage($nextPage)
    {
        $this->nextPage = $nextPage;
    }

    /**
     * @return mixed
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * @param mixed $prevPage
     */
    public function setPrevPage($prevPage)
    {
        $this->prevPage = $prevPage;
    }

    /**
     * @return mixed
     */
    public function getPrevPage()
    {
        return $this->prevPage;
    }

    /**
     * @param mixed $firstPage
     */
    public function setFirstPage($firstPage)
    {
        $this->firstPage = $firstPage;
    }

    /**
     * @return mixed
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * @param mixed $lastPage
     */
    public function setLastPage($lastPage)
    {
        $this->lastPage = $lastPage;
    }

    /**
     * @return mixed
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * @param mixed $dream_equipment_progress
     */
    public function setDream_equipment_progress($dream_equipment_progress)
    {
        $this->dream_equipment_progress = $dream_equipment_progress;
    }
    /**
     * @return mixed
     */
    public function getDream_equipment_progress()
    {
        return $this->dream_equipment_progress;
    }

    /**
     * @param mixed $dream_financial_progress
     */
    public function setDream_financial_progress($dream_financial_progress)
    {
        $this->dream_financial_progress = $dream_financial_progress;
    }
    /**
     * @return mixed
     */
    public function getDream_financial_progress()
    {
        return $this->dream_financial_progress;
    }

    /**
     * @param mixed $dream_work_progress
     */
    public function setDream_work_progress($dream_work_progress)
    {
        $this->dream_work_progress = $dream_work_progress;
    }
    /**
     * @return mixed
     */
    public function getDream_work_progress()
    {
        return $this->dream_work_progress;
    }

}
