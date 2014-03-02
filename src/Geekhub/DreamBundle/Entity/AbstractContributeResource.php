<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Geekhub\ResourceBundle\Entity\Timestampable;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractContributeResource
{
    use Timestampable;

    const FINANCIAL     = 'financial';
    const EQUIPMENT     = 'equipment';
    const WORK          = 'work';
    const OTHER         = 'other';
    const TON           = 'ton';
    const KG            = 'kg';
    const PIECE         = 'piece';

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "dreamResource.title.not_blank")
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30)
     */
    protected $type;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="quantityType", type="string", length=15)
     */
    protected $quantityType;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantityDays", type="integer")
     */
    protected $quantityDays;

    /**
     * Set title
     *
     * @param  string        $title
     * @return DreamResource
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param  string        $type
     * @return DreamResource
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set quantity
     *
     * @param  float         $quantity
     * @return DreamResource
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantityType
     *
     * @param  string        $quantityType
     * @return DreamResource
     */
    public function setQuantityType($quantityType)
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    /**
     * Get quantityType
     *
     * @return string
     */
    public function getQuantityType()
    {
        return $this->quantityType;
    }

    /**
     * Set quantityDays
     *
     * @param  integer       $quantityDays
     * @return DreamResource
     */
    public function setQuantityDays($quantityDays)
    {
        $this->quantityDays = $quantityDays;

        return $this;
    }

    /**
     * Get quantityDays
     *
     * @return integer
     */
    public function getQuantityDays()
    {
        return $this->quantityDays;
    }
}
