<?php

namespace Geekhub\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Geekhub\UserBundle\Entity\UsersRepository")
 */
class User extends BaseUser //implements DreamUserInterface
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
     * @var Contacts
     *
     * @ORM\Column(name="contacts", type="object")
     */
    protected $contacts;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    protected $about;

    /**
     * @var string
     *
     * @ORM\Column(name="vkontakte_id", type="string", length=45, nullable=true)
     */
    protected $vkontakteId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=45, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="odnoklassniki_id", type="string", length=45, nullable=true)
     */
    protected $odnoklassnikiId;

    /**
     * @ORM\ManyToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="usersWhoFavorites")
     */
    protected $favoriteDreams;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\DreamContribute", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $contributions;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userComments;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="author")
     */
    protected $dreams;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts       = new Contacts();
        $this->favoriteDreams = new ArrayCollection();
        $this->contributions  = new ArrayCollection();
        $this->userComments   = new ArrayCollection();
        $this->dreams         = new ArrayCollection();

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
     * Set contacts
     *
     * @param  Contacts $contacts
     * @return User
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return Contacts
     */
    public function getContacts()
    {
        return $this->contacts;
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
     * Add contributions
     *
     * @param  \Geekhub\DreamBundle\Entity\DreamContribute $contributions
     * @return User
     */
    public function addContribution(\Geekhub\DreamBundle\Entity\DreamContribute $contributions)
    {
        $this->contributions[] = $contributions;

        return $this;
    }

    /**
     * Remove contributions
     *
     * @param \Geekhub\DreamBundle\Entity\DreamContribute $contributions
     */
    public function removeContribution(\Geekhub\DreamBundle\Entity\DreamContribute $contributions)
    {
        $this->contributions->removeElement($contributions);
    }

    /**
     * Get contributions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContributions()
    {
        return $this->contributions;
    }

    /**
     * Add userComments
     *
     * @param  \Geekhub\DreamBundle\Entity\Comment $userComments
     * @return User
     */
    public function addUserComment(\Geekhub\DreamBundle\Entity\Comment $userComments)
    {
        $this->userComments[] = $userComments;

        return $this;
    }

    /**
     * Remove userComments
     *
     * @param \Geekhub\DreamBundle\Entity\Comment $userComments
     */
    public function removeUserComment(\Geekhub\DreamBundle\Entity\Comment $userComments)
    {
        $this->userComments->removeElement($userComments);
    }

    /**
     * Get userComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserComments()
    {
        return $this->userComments;
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
}
