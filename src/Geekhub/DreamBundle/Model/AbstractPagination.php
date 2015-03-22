<?php

namespace Geekhub\DreamBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Geekhub\DreamBundle\Model\PaginationInterface;

/**
 * Class AbstractPagination
 * @package Geekhub\DreamBundle\Model
 * @ExclusionPolicy("all")
 */
abstract class AbstractPagination implements PaginationInterface
{
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
     * @var string
     *
     * @Type("string")
     * @Expose
     */
    protected $firstPage;
    /**
     * @var string
     *
     * @Type("string")
     * @Expose
     */
    protected $lastPage;

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
}
