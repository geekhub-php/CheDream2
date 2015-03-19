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
class DreamsResponse
{
    /**
     * @var Array[]
     *
     * @Type("array<Geekhub\DreamBundle\Entity\Dream>")
     * @Expose
     */
    protected $dreams;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     */
    protected $selfPage;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     */
    protected $nextPage;

    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     */
    protected $prevPage;

    /**
     * @var integer
     *
     * @Type("integer")
     * @Expose
     */
    protected $firstPage;


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
}