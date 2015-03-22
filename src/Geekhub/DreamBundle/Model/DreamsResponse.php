<?php

namespace Geekhub\DreamBundle\Model;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use Geekhub\DreamBundle\Model\AbstractPagination;

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
}
