<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Dreams_needing
 *
 * @ORM\Table(name="dreamResources")
 * @ORM\Entity()
 */
class DreamResources
{
    const FINANCIAL     = 'financial';
    const EQUIPMENT     = 'equipment';
    const WORK          = 'work';
    const OTHER         = 'other';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @Assert\NotBlank(message = "dreamNeeding.title.not_blank")
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    /**
     * @var integer
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
     * @var integer
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected $hiddenContributor;



    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamResources")
     */
    protected $dream;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="userResources")
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return DreamResources
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
     * @param string $title
     * @return DreamResources
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
     * @return DreamResources
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
     * Set quantity
     *
     * @param float $quantity
     * @return DreamResources
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
     * @param integer $quantityType
     * @return DreamResources
     */
    public function setQuantityType($quantityType)
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    /**
     * Get quantityType
     *
     * @return integer 
     */
    public function getQuantityType()
    {
        return $this->quantityType;
    }

    /**
     * Set quantityDays
     *
     * @param integer $quantityDays
     * @return DreamResources
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

    /**
     * Set description
     *
     * @param string $description
     * @return DreamResources
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
     * Set hiddenContributor
     *
     * @param boolean $hiddenContributor
     * @return DreamResources
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
     * @return DreamResources
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
     * @return DreamResources
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
