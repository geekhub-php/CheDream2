<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractContributeResource
{
    const TON   = 'ton';
    const KG    = 'kg';
    const PIECE = 'piece';

    static public function getReadableQuantityTypes()
    {
        return array(
            self::PIECE => 'dream.equipment.piece',
            self::KG    => 'dream.equipment.kg',
            self::TON   => 'dream.equipment.ton'
        );
    }

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "dream.not_blank")
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "dream.not_blank")
     * @Assert\Regex(pattern="/^[0-9.]+$/", message="dream.only_numbers")
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

    /**
     * Set createdAt
     *
     * @param  \DateTime     $createdAt
     * @return DreamResource
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

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
}
