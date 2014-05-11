<?php

namespace Geekhub\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Geekhub\UserBundle\Entity\UsersRepository")
 */
class User extends BaseUser //implements DreamUserInterface
{
    use ContactsInfo;

    const FAKE_EMAIL_PART = "@example.com";

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
     * @Assert\NotBlank()
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middleName", type="string", length=50, nullable=true)
     */
    protected $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade="all")
     */
    protected $avatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakte_id", type="string", length=45, nullable=true, unique=true)
     */
    protected $vkontakteId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=45, nullable=true, unique=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="odnoklassniki_id", type="string", length=45, nullable=true, unique=true)
     */
    protected $odnoklassnikiId;

    /**
     * @ORM\ManyToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="usersWhoFavorites", cascade={"persist", "remove"})
     */
    protected $favoriteDreams;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\FinancialContribute", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $financialContributions;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\EquipmentContribute", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $equipmentContributions;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\WorkContribute", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $workContributions;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\OtherContribute", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $otherContributions;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="author")
     */
    protected $dreams;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->favoriteDreams = new ArrayCollection();
        $this->dreams         = new ArrayCollection();
        $this->financialContributions = new ArrayCollection();
        $this->equipmentContributions = new ArrayCollection();
        $this->workContributions = new ArrayCollection();
        $this->otherContributions = new ArrayCollection();
        parent::__construct();
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
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param  string $middleName
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set avatar
     *
     * @param  string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set birthday
     *
     * @param  \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set about
     *
     * @param  string $about
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set vk
     *
     * @param  string $vk
     * @return User
     */
    public function setVkontakteId($vk)
    {
        $this->vkontakteId = $vk;

        return $this;
    }

    /**
     * Get vkontakte_id
     *
     * @return string
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    /**
     * Set facebook
     *
     * @param  string $facebook
     * @return User
     */
    public function setFacebookId($facebook)
    {
        $this->facebookId = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set odnoklasnikiId
     *
     * @param  string $odnoklasnikiId
     * @return string
     */
    public function setOdnoklassnikiId($odnoklassnikiId)
    {
        $this->odnoklassnikiId = $odnoklassnikiId;

        return $this;
    }

    /**
     * Get odnoklasnikiId
     *
     * @return string
     */
    public function getOdnoklassnikiId()
    {
        return $this->odnoklassnikiId;
    }

    /**
     * Add favoriteDreams
     *
     * @param  \Geekhub\DreamBundle\Entity\Dream $favoriteDreams
     * @return User
     */
    public function addFavoriteDream(\Geekhub\DreamBundle\Entity\Dream $favoriteDreams)
    {
        $this->favoriteDreams[] = $favoriteDreams;
        $favoriteDreams->addUsersWhoFavorite($this);

        return $this;
    }

    /**
     * Remove favoriteDreams
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $favoriteDreams
     */
    public function removeFavoriteDream(\Geekhub\DreamBundle\Entity\Dream $favoriteDreams)
    {
        $this->favoriteDreams->removeElement($favoriteDreams);
        $favoriteDreams->removeUsersWhoFavorite($this);
    }

    /**
     * Get favoriteDreams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteDreams()
    {
        return $this->favoriteDreams;
    }

    /**
     * Add dreams
     *
     * @param  \Geekhub\DreamBundle\Entity\Dream $dreams
     * @return User
     */
    public function addDream(\Geekhub\DreamBundle\Entity\Dream $dreams)
    {
        $this->dreams[] = $dreams;

        return $this;
    }

    /**
     * Remove dreams
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dreams
     */
    public function removeDream(\Geekhub\DreamBundle\Entity\Dream $dreams)
    {
        $this->dreams->removeElement($dreams);
    }

    /**
     * Get dreams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDreams()
    {
        return $this->dreams;
    }

    /**
     * Add financialContributions
     *
     * @param  \Geekhub\DreamBundle\Entity\FinancialContribute $financialContributions
     * @return User
     */
    public function addFinancialContribution(\Geekhub\DreamBundle\Entity\FinancialContribute $financialContributions)
    {
        $this->financialContributions[] = $financialContributions;

        return $this;
    }

    /**
     * Remove financialContributions
     *
     * @param \Geekhub\DreamBundle\Entity\FinancialContribute $financialContributions
     */
    public function removeFinancialContribution(\Geekhub\DreamBundle\Entity\FinancialContribute $financialContributions)
    {
        $this->financialContributions->removeElement($financialContributions);
    }

    /**
     * Get financialContributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFinancialContributions()
    {
        return $this->financialContributions;
    }

    /**
     * Add equipmentContributions
     *
     * @param  \Geekhub\DreamBundle\Entity\EquipmentContribute $equipmentContributions
     * @return User
     */
    public function addEquipmentContribution(\Geekhub\DreamBundle\Entity\EquipmentContribute $equipmentContributions)
    {
        $this->equipmentContributions[] = $equipmentContributions;

        return $this;
    }

    /**
     * Remove equipmentContributions
     *
     * @param \Geekhub\DreamBundle\Entity\EquipmentContribute $equipmentContributions
     */
    public function removeEquipmentContribution(\Geekhub\DreamBundle\Entity\EquipmentContribute $equipmentContributions)
    {
        $this->equipmentContributions->removeElement($equipmentContributions);
    }

    /**
     * Get equipmentContributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipmentContributions()
    {
        return $this->equipmentContributions;
    }

    /**
     * Add workContributions
     *
     * @param  \Geekhub\DreamBundle\Entity\WorkContribute $workContributions
     * @return User
     */
    public function addWorkContribution(\Geekhub\DreamBundle\Entity\WorkContribute $workContributions)
    {
        $this->workContributions[] = $workContributions;

        return $this;
    }

    /**
     * Remove workContributions
     *
     * @param \Geekhub\DreamBundle\Entity\WorkContribute $workContributions
     */
    public function removeWorkContribution(\Geekhub\DreamBundle\Entity\WorkContribute $workContributions)
    {
        $this->workContributions->removeElement($workContributions);
    }

    /**
     * Get workContributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkContributions()
    {
        return $this->workContributions;
    }

    /**
     * Add otherContributions
     *
     * @param  \Geekhub\DreamBundle\Entity\OtherContribute $otherContributions
     * @return User
     */
    public function addOtherContribution(\Geekhub\DreamBundle\Entity\OtherContribute $otherContributions)
    {
        $this->otherContributions[] = $otherContributions;

        return $this;
    }

    /**
     * Remove otherContributions
     *
     * @param \Geekhub\DreamBundle\Entity\OtherContribute $otherContributions
     */
    public function removeOtherContribution(\Geekhub\DreamBundle\Entity\OtherContribute $otherContributions)
    {
        $this->otherContributions->removeElement($otherContributions);
    }

    /**
     * Get otherContributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOtherContributions()
    {
        return $this->otherContributions;
    }

    /**
     * @return array
     */
    public function getNotNullSocialIds()
    {
        return array_filter([
                'facebook' => $this->facebookId,
                'vkontakte' => $this->vkontakteId,
                'odnoklassniki' => $this->odnoklassnikiId,
            ], 'strlen')
        ;
    }

    /**
     * @return bool
     */
    public function isFakeEmail()
    {
        return false === strpos($this->email, self::FAKE_EMAIL_PART) && $this->email ? false : true;
    }
}
