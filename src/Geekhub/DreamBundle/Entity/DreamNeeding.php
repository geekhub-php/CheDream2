<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dreams_needing
 *
 * @ORM\Table(name="dreamNeeding")
 * @ORM\Entity()
 */
class DreamNeeding
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "dreamNeeding.title.not_blank")
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    protected $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="amountType", type="smallint")
     */
    protected $amountType;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="countDays", type="integer")
     */
    protected $countDays;

    /**
     * @var integer
     *
     * @ORM\Column(name="countPeople", type="integer")
     */
    protected $countPeople;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected $hiddenContributor;

    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamNeedings")
     */
    protected $dream;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="userNeedings")
     */
    protected $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Dreams_needing
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
     * @param integer $type
     * @return Dreams_needing
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Dreams_needing
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amountType
     *
     * @param integer $amountType
     * @return Dreams_needing
     */
    public function setAmountType($amountType)
    {
        $this->amountType = $amountType;

        return $this;
    }

    /**
     * Get amountType
     *
     * @return integer 
     */
    public function getAmountType()
    {
        return $this->amountType;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Dreams_needing
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set countDays
     *
     * @param integer $countDays
     * @return Dreams_needing
     */
    public function setCountDays($countDays)
    {
        $this->countDays = $countDays;

        return $this;
    }

    /**
     * Get countDays
     *
     * @return integer 
     */
    public function getCountDays()
    {
        return $this->countDays;
    }

    /**
     * Set countPeople
     *
     * @param integer $countPeople
     * @return Dreams_needing
     */
    public function setCountPeople($countPeople)
    {
        $this->countPeople = $countPeople;

        return $this;
    }

    /**
     * Get countPeople
     *
     * @return integer 
     */
    public function getCountPeople()
    {
        return $this->countPeople;
    }

    /**
     * Set hiddenContributor
     *
     * @param boolean $hiddenContributor
     * @return Dreams_needing
     */
    public function setHiddenContributor($hiddenContributor)
    {
        $this->hiddenContributor = $hiddenContributor;

        return $this;
    }

    /**
     * Get hiddenContributor
     *
     * @return boolean 
     */
    public function getHiddenContributor()
    {
        return $this->hiddenContributor;
    }

    /**
     * Set dream
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dream
     * @return DreamNeeding
     */
    public function setDream(\Geekhub\DreamBundle\Entity\Dream $dream = null)
    {
        $this->dream = $dream;

        return $this;
    }

    /**
     * Get dream
     *
     * @return \Geekhub\DreamBundle\Entity\Dream 
     */
    public function getDream()
    {
        return $this->dream;
    }

    /**
     * Set user
     *
     * @param \Geekhub\UserBundle\Entity\User $user
     * @return DreamNeeding
     */
    public function setUser(\Geekhub\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Geekhub\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
