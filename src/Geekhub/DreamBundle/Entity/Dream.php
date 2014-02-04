<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dreams
 *
 * @ORM\Table(name="dreams")
 * @ORM\Entity(repositoryClass="Geekhub\DreamBundle\Entity\DreamRepository")
 */
class Dream
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPicture", type="string", length=100)
     */
    private $mainPicture;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=200)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletedAt", type="datetime")
     */
    private $deletedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredDate", type="datetime")
     */
    private $expiredDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="financialCompleted", type="smallint")
     */
    private $financialCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="workCompleted", type="smallint")
     */
    private $workCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="equipmentCompleted", type="smallint")
     */
    private $equipmentCompleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenPhone", type="boolean")
     */
    private $hiddenPhone;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="dreams")
     */
    protected $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="favoriteDreams")
     * @ORM\JoinTable(name="favorite_dreams")
     */
    protected $usersWhoFavorites;

    /**
     * @ORM\OneToMany(targetEntity="DreamNeeding", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamNeedings;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamComments;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamMessages;

    /**
     * @ORM\OneToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="dream")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * @return Dreams
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
     * Set description
     *
     * @param string $description
     * @return Dreams
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
     * Set mainPicture
     *
     * @param string $mainPicture
     * @return Dreams
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return string 
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Dreams
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Dreams
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Dreams
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Dreams
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Dreams
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Dreams
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set expiredDate
     *
     * @param \DateTime $expiredDate
     * @return Dreams
     */
    public function setExpiredDate($expiredDate)
    {
        $this->expiredDate = $expiredDate;

        return $this;
    }

    /**
     * Get expiredDate
     *
     * @return \DateTime 
     */
    public function getExpiredDate()
    {
        return $this->expiredDate;
    }

    /**
     * Set financialCompleted
     *
     * @param integer $financialCompleted
     * @return Dreams
     */
    public function setFinancialCompleted($financialCompleted)
    {
        $this->financialCompleted = $financialCompleted;

        return $this;
    }

    /**
     * Get financialCompleted
     *
     * @return integer 
     */
    public function getFinancialCompleted()
    {
        return $this->financialCompleted;
    }

    /**
     * Set workCompleted
     *
     * @param integer $workCompleted
     * @return Dreams
     */
    public function setWorkCompleted($workCompleted)
    {
        $this->workCompleted = $workCompleted;

        return $this;
    }

    /**
     * Get workCompleted
     *
     * @return integer 
     */
    public function getWorkCompleted()
    {
        return $this->workCompleted;
    }

    /**
     * Set equipmentCompleted
     *
     * @param integer $equipmentCompleted
     * @return Dreams
     */
    public function setEquipmentCompleted($equipmentCompleted)
    {
        $this->equipmentCompleted = $equipmentCompleted;

        return $this;
    }

    /**
     * Get equipmentCompleted
     *
     * @return integer 
     */
    public function getEquipmentCompleted()
    {
        return $this->equipmentCompleted;
    }

    /**
     * Set hiddenPhone
     *
     * @param boolean $hiddenPhone
     * @return Dreams
     */
    public function setHiddenPhone($hiddenPhone)
    {
        $this->hiddenPhone = $hiddenPhone;

        return $this;
    }

    /**
     * Get hiddenPhone
     *
     * @return boolean 
     */
    public function getHiddenPhone()
    {
        return $this->hiddenPhone;
    }
}
