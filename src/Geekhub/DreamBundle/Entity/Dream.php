<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Dreams
 *
 * @ORM\Table(name="dreams")
 * @ORM\Entity(repositoryClass="Geekhub\DreamBundle\Entity\DreamRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Dream implements Taggable
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
     * @ORM\Column(name="phone", type="string", length=45, nullable=true)
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
     * @ORM\Column(name="expiredDate", type="date", nullable=true)
     */
    protected $expiredDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="financialCompleted", type="smallint", nullable=true)
     */
    protected $financialCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="workCompleted", type="smallint", nullable=true)
     */
    protected $workCompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="equipmentCompleted", type="smallint", nullable=true)
     */
    protected $equipmentCompleted;

    protected $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="favoriteDreams")
     * @ORM\JoinTable(name="favorite_dreams")
     */
    protected $usersWhoFavorites;

    /**
     * @ORM\OneToMany(targetEntity="DreamContribute", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamContributes;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamComments;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="dreams")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ORM\OneToMany(targetEntity="Status", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $statuses;

       /**
     * @ORM\Column(name="currentStatus", type="object", nullable = true)
     */
    protected $currentStatus;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="mediaPictures_media")
     */
    protected $mediaPictures;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     */
    protected $mediaPoster;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="mediaFiles_media")
     */
    protected $mediaFiles;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="mediaVideos_media")
     */
    protected $mediaVideos;

    protected $dreamPictures;
    protected $dreamPoster;
    protected $dreamFiles;
    protected $dreamVideos;

    /**
     * @ORM\OneToMany(targetEntity="FinancialResource", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamFinancialResources;

    /**
     * @ORM\OneToMany(targetEntity="EquipmentResource", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamEquipmentResources;

    /**
     * @ORM\OneToMany(targetEntity="WorkResource", mappedBy="dream", cascade={"persist", "remove"})
     */
    protected $dreamWorkResources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usersWhoFavorites = new ArrayCollection();
        $this->dreamContributes = new ArrayCollection();
        $this->dreamComments = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->mediaPictures = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();
        $this->mediaVideos = new ArrayCollection();
        $this->dreamFinancialResources = new ArrayCollection();
        $this->dreamEquipmentResources = new ArrayCollection();
        $this->dreamWorkResources = new ArrayCollection();
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
     * @param  string $title
     * @return Dream
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
     * @param  string $description
     * @return Dream
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
     * Set phone
     *
     * @param  string $phone
     * @return Dream
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
     * @param  string $slug
     * @return Dream
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
     * @param  \DateTime $createdAt
     * @return Dream
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
     * @param  \DateTime $updatedAt
     * @return Dream
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
     * @param  \DateTime $deletedAt
     * @return Dream
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
     * @param  \DateTime $expiredDate
     * @return Dream
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
     * @param  integer $financialCompleted
     * @return Dream
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
     * @param  integer $workCompleted
     * @return Dream
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
     * @param  integer $equipmentCompleted
     * @return Dream
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
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'dream_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Add usersWhoFavorites
     *
     * @param  \Geekhub\UserBundle\Entity\User $usersWhoFavorites
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
     * Add dreamContributes
     *
     * @param  \Geekhub\DreamBundle\Entity\DreamContribute $dreamContributes
     * @return Dream
     */
    public function addDreamContribute(\Geekhub\DreamBundle\Entity\DreamContribute $dreamContributes)
    {
        $this->dreamContributes[] = $dreamContributes;

        return $this;
    }

    /**
     * Remove dreamContributes
     *
     * @param \Geekhub\DreamBundle\Entity\DreamContribute $dreamContributes
     */
    public function removeDreamContribute(\Geekhub\DreamBundle\Entity\DreamContribute $dreamContributes)
    {
        $this->dreamContributes->removeElement($dreamContributes);
    }

    /**
     * Get dreamContributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreamContributes()
    {
        return $this->dreamContributes;
    }

    /**
     * Add dreamComments
     *
     * @param  \Geekhub\DreamBundle\Entity\Comment $dreamComments
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
     * Set author
     *
     * @param  \Geekhub\UserBundle\Entity\User $author
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
     * Add status
     *
     * @param  Status $status
     * @return Dream
     */
    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
//        $status->setDream($this);
        $this->currentStatus = $status;

        return $this;
    }

    /**
     * Remove statuses
     *
     * @param \Geekhub\DreamBundle\Entity\Status $statuses
     */
    public function removeStatus(\Geekhub\DreamBundle\Entity\Status $statuses)
    {
        $this->statuses->removeElement($statuses);
    }

    /**
     * Get statuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * Set currentStatus
     *
     * @param  string $currentStatus
     * @return Dream
     */
    public function setCurrentStatus($currentStatus)
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return string
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @param mixed $dreamPictures
     */
    public function setDreamPictures($dreamPictures)
    {
        $this->dreamPictures = $dreamPictures;
    }

    /**
     * @return mixed
     */
    public function getDreamPictures()
    {
        return $this->dreamPictures;
    }

    /**
     * @param mixed $dreamFiles
     */
    public function setDreamFiles($dreamFiles)
    {
        $this->dreamFiles = $dreamFiles;
    }

    /**
     * @return mixed
     */
    public function getDreamFiles()
    {
        return $this->dreamFiles;
    }

    /**
     * @param mixed $dreamPoster
     */
    public function setDreamPoster($dreamPoster)
    {
        $this->dreamPoster = $dreamPoster;
    }

    /**
     * @return mixed
     */
    public function getDreamPoster()
    {
        return $this->dreamPoster;
    }

    /**
     * @param mixed $dreamVideos
     */
    public function setDreamVideos($dreamVideos)
    {
        $this->dreamVideos = $dreamVideos;
    }

    /**
     * @return mixed
     */
    public function getDreamVideos()
    {
        return $this->dreamVideos;
    }

    /**
     * Add mediaPictures
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaPictures
     *
     * @return Dream
     */
    public function addMediaPicture(\Application\Sonata\MediaBundle\Entity\Media $mediaPictures)
    {
        $this->mediaPictures[] = $mediaPictures;

        return $this;
    }

    /**
     * Remove mediaPictures
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaPictures
     */
    public function removeMediaPicture(\Application\Sonata\MediaBundle\Entity\Media $mediaPictures)
    {
        $this->mediaPictures->removeElement($mediaPictures);
    }

    /**
     * Get mediaPictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaPictures()
    {
        return $this->mediaPictures;
    }

    /**
     * Set mediaPoster
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaPoster
     *
     * @return Dream
     */
    public function setMediaPoster(\Application\Sonata\MediaBundle\Entity\Media $mediaPoster = null)
    {
        $this->mediaPoster = $mediaPoster;

        return $this;
    }

    /**
     * Get mediaPoster
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMediaPoster()
    {
        return $this->mediaPoster;
    }

    /**
     * Add mediaFiles
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaFiles
     *
     * @return Dream
     */
    public function addMediaFile(\Application\Sonata\MediaBundle\Entity\Media $mediaFiles)
    {
        $this->mediaFiles[] = $mediaFiles;

        return $this;
    }

    /**
     * Remove mediaFiles
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaFiles
     */
    public function removeMediaFile(\Application\Sonata\MediaBundle\Entity\Media $mediaFiles)
    {
        $this->mediaFiles->removeElement($mediaFiles);
    }

    /**
     * Get mediaFiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    /**
     * Add mediaVideos
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaVideos
     *
     * @return Dream
     */
    public function addMediaVideo(\Application\Sonata\MediaBundle\Entity\Media $mediaVideos)
    {
        $this->mediaVideos[] = $mediaVideos;

        return $this;
    }

    /**
     * Remove mediaVideos
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $mediaVideos
     */
    public function removeMediaVideo(\Application\Sonata\MediaBundle\Entity\Media $mediaVideos)
    {
        $this->mediaVideos->removeElement($mediaVideos);
    }

    /**
     * Get mediaVideos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaVideos()
    {
        return $this->mediaVideos;
    }

    /**
     * Add dreamFinancialResources
     *
     * @param  FinancialResource $dreamFinancialResources
     * @return Dream
     */
    public function addDreamFinancialResource(FinancialResource $dreamFinancialResources)
    {
        $this->dreamFinancialResources[] = $dreamFinancialResources;
        $dreamFinancialResources->setDream($this);

        return $this;
    }

    /**
     * Remove dreamFinancialResources
     *
     * @param FinancialResource $dreamFinancialResources
     */
    public function removeDreamFinancialResource(FinancialResource $dreamFinancialResources)
    {
        $this->dreamFinancialResources->removeElement($dreamFinancialResources);
        $dreamFinancialResources->setDream(null);
    }

    /**
     * Get dreamFinancialResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreamFinancialResources()
    {
        return $this->dreamFinancialResources;
    }

    /**
     * Add dreamEquipmentResources
     *
     * @param  EquipmentResource $dreamEquipmentResources
     * @return Dream
     */
    public function addDreamEquipmentResource(EquipmentResource $dreamEquipmentResources)
    {
        $this->dreamEquipmentResources[] = $dreamEquipmentResources;
        $dreamEquipmentResources->setDream($this);

        return $this;
    }

    /**
     * Remove dreamEquipmentResources
     *
     * @param EquipmentResource $dreamEquipmentResources
     */
    public function removeDreamEquipmentResource(EquipmentResource $dreamEquipmentResources)
    {
        $this->dreamEquipmentResources->removeElement($dreamEquipmentResources);
        $dreamEquipmentResources->setDream(null);
    }

    /**
     * Get dreamEquipmentResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreamEquipmentResources()
    {
        return $this->dreamEquipmentResources;
    }

    /**
     * Add dreamWorkResources
     *
     * @param  WorkResource $dreamWorkResources
     * @return Dream
     */
    public function addDreamWorkResource(WorkResource $dreamWorkResources)
    {
        $this->dreamWorkResources[] = $dreamWorkResources;
        $dreamWorkResources->setDream($this);

        return $this;
    }

    /**
     * Remove dreamWorkResources
     *
     * @param WorkResource $dreamWorkResources
     */
    public function removeDreamWorkResource(WorkResource $dreamWorkResources)
    {
        $this->dreamWorkResources->removeElement($dreamWorkResources);
        $dreamWorkResources->setDream(null);
    }

    /**
     * Get dreamWorkResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreamWorkResources()
    {
        return $this->dreamWorkResources;
    }
}
