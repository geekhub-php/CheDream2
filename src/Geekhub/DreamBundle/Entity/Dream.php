<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Dreams
 *
 * @ORM\Table(name="dreams")
 * @ORM\Entity(repositoryClass="Geekhub\DreamBundle\Entity\DreamRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
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
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "dream.title.not_blank")
     * @Assert\Length(min = "5", minMessage = "dream.title.length_error_short")
     * @ORM\Column(name="title", type="string", length=200)
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "dream.description.not_blank")
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPicture", type="string", length=100)
     */
    protected $mainPicture;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45)
     */
    protected $phone;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=200, unique=true)
     */
    protected $slug;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredDate", type="datetime")
     */
    protected $expiredDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="financialCompleted", type="smallint")
     */
    protected $financialCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="workCompleted", type="smallint")
     */
    protected $workCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="equipmentCompleted", type="smallint")
     */
    protected $equipmentCompleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenPhone", type="boolean")
     */
    protected $hiddenPhone;

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
     * @ORM\OneToMany(targetEntity="DreamResources", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamResources;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamComments;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamMessages;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="dreams")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var boolean
     *
     * @ORM\Column(name="submitted", type="boolean")
     */
    protected $submitted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rejected", type="boolean")
     */
    protected $rejected;

    /**
     * @ORM\OneToOne(targetEntity="Status", mappedBy="dream")
     */
    protected $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->usersWhoFavorites = new ArrayCollection();
        $this->dreamResources = new ArrayCollection();
        $this->dreamComments = new ArrayCollection();
        $this->dreamMessages = new ArrayCollection();
    }

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

    /**
     * Add tags
     *
     * @param \Geekhub\DreamBundle\Entity\Tag $tags
     * @return Dream
     */
    public function addTag(\Geekhub\DreamBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Geekhub\DreamBundle\Entity\Tag $tags
     */
    public function removeTag(\Geekhub\DreamBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add usersWhoFavorites
     *
     * @param \Geekhub\UserBundle\Entity\User $usersWhoFavorites
     * @return Dream
     */
    public function addUsersWhoFavorite(\Geekhub\UserBundle\Entity\User $usersWhoFavorites)
    {
        $this->usersWhoFavorites[] = $usersWhoFavorites;

        return $this;
    }

    /**
     * Remove usersWhoFavorites
     *
     * @param \Geekhub\UserBundle\Entity\User $usersWhoFavorites
     */
    public function removeUsersWhoFavorite(\Geekhub\UserBundle\Entity\User $usersWhoFavorites)
    {
        $this->usersWhoFavorites->removeElement($usersWhoFavorites);
    }

    /**
     * Get usersWhoFavorites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersWhoFavorites()
    {
        return $this->usersWhoFavorites;
    }

    /**
     * Add dreamResources
     *
     * @param \Geekhub\DreamBundle\Entity\DreamResources $dreamResources
     * @return Dream
     */
    public function addDreamResource(\Geekhub\DreamBundle\Entity\DreamResources $dreamResources)
    {
        $this->dreamResources[] = $dreamResources;

        return $this;
    }

    /**
     * Remove dreamResources
     *
     * @param \Geekhub\DreamBundle\Entity\DreamResources $dreamResources
     */
    public function removeDreamResource(\Geekhub\DreamBundle\Entity\DreamResources $dreamResources)
    {
        $this->dreamResources->removeElement($dreamResources);
    }

    /**
     * Get dreamResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreamResources()
    {
        return $this->dreamResources;
    }

    /**
     * Add dreamComments
     *
     * @param \Geekhub\DreamBundle\Entity\Comment $dreamComments
     * @return Dream
     */
    public function addDreamComment(\Geekhub\DreamBundle\Entity\Comment $dreamComments)
    {
        $this->dreamComments[] = $dreamComments;

        return $this;
    }

    /**
     * Remove dreamComments
     *
     * @param \Geekhub\DreamBundle\Entity\Comment $dreamComments
     */
    public function removeDreamComment(\Geekhub\DreamBundle\Entity\Comment $dreamComments)
    {
        $this->dreamComments->removeElement($dreamComments);
    }

    /**
     * Get dreamComments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDreamComments()
    {
        return $this->dreamComments;
    }

    /**
     * Add dreamMessages
     *
     * @param \Geekhub\DreamBundle\Entity\Message $dreamMessages
     * @return Dream
     */
    public function addDreamMessage(\Geekhub\DreamBundle\Entity\Message $dreamMessages)
    {
        $this->dreamMessages[] = $dreamMessages;

        return $this;
    }

    /**
     * Remove dreamMessages
     *
     * @param \Geekhub\DreamBundle\Entity\Message $dreamMessages
     */
    public function removeDreamMessage(\Geekhub\DreamBundle\Entity\Message $dreamMessages)
    {
        $this->dreamMessages->removeElement($dreamMessages);
    }

    /**
     * Get dreamMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDreamMessages()
    {
        return $this->dreamMessages;
    }

    /**
     * Set author
     *
     * @param \Geekhub\UserBundle\Entity\User $author
     * @return Dream
     */
    public function setAuthor(\Geekhub\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Geekhub\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set submitted
     *
     * @param boolean $submitted
     * @return Dream
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return boolean 
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set rejected
     *
     * @param boolean $rejected
     * @return Dream
     */
    public function setRejected($rejected)
    {
        $this->rejected = $rejected;

        return $this;
    }

    /**
     * Get rejected
     *
     * @return boolean 
     */
    public function getRejected()
    {
        return $this->rejected;
    }

    /**
     * Set status
     *
     * @param \Geekhub\DreamBundle\Entity\Status $status
     * @return Dream
     */
    public function setStatus(\Geekhub\DreamBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Geekhub\DreamBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
