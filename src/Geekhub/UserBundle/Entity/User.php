<?php

namespace Geekhub\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Geekhub\DreamBundle\Entity\Dream;
//use Geekhub\UserBundle\Entity\DreamUserInterface as DreamUserInterface;

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
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middleName", type="string", length=50, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=100, nullable=true)
     */
    private $avatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=45, nullable=true)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="vk", type="string", length=45, nullable=true)
     */
    private $vk;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=45, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="odnoklasniki", type="string", length=45, nullable=true)
     */
    private $odnoklasniki;

    /**
     * @ORM\ManyToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="usersWhoFavorites")
     */
    protected  $favoriteDreams;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\DreamResources", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userResources;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userComments;

    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Message", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userMessages;


    /**
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="author")
     */
    protected  $dreams;

    /**
     * Get id
     *
     * return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Users
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
     * @param string $middleName
     * @return Users
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
     * @param string $lastName
     * @return Users
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
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Users
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
     * @param \DateTime $birthday
     * @return Users
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
     * Set phone
     *
     * @param string $phone
     * @return Users
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
     * Set about
     *
     * @param string $about
     * @return Users
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
     * Set skype
     *
     * @param string $skype
     * @return Users
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set vk
     *
     * @param string $vk
     * @return Users
     */
    public function setVk($vk)
    {
        $this->vk = $vk;

        return $this;
    }

    /**
     * Get vk
     *
     * @return string 
     */
    public function getVk()
    {
        return $this->vk;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Users
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set odnoklasniki
     *
     * @param string $odnoklasniki
     * @return Users
     */
    public function setOdnoklasniki($odnoklasniki)
    {
        $this->odnoklasniki = $odnoklasniki;

        return $this;
    }

    /**
     * Get odnoklasniki
     *
     * @return string 
     */
    public function getOdnoklasniki()
    {
        return $this->odnoklasniki;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userResources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userComments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userMessages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userResources
     *
     * @param \Geekhub\DreamBundle\Entity\DreamResources $userResources
     * @return User
     */
    public function addUserResource(\Geekhub\DreamBundle\Entity\DreamResources $userResources)
    {
        $this->userResources[] = $userResources;

        return $this;
    }

    /**
     * Remove userResources
     *
     * @param \Geekhub\DreamBundle\Entity\DreamResources $userResources
     */
    public function removeUserResource(\Geekhub\DreamBundle\Entity\DreamResources $userResources)
    {
        $this->userResources->removeElement($userResources);
    }

    /**
     * Get userResources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserResources()
    {
        return $this->userResources;
    }

    /**
     * Add userComments
     *
     * @param \Geekhub\DreamBundle\Entity\Comment $userComments
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
     * Add userMessages
     *
     * @param \Geekhub\DreamBundle\Entity\Message $userMessages
     * @return User
     */
    public function addUserMessage(\Geekhub\DreamBundle\Entity\Message $userMessages)
    {
        $this->userMessages[] = $userMessages;

        return $this;
    }

    /**
     * Remove userMessages
     *
     * @param \Geekhub\DreamBundle\Entity\Message $userMessages
     */
    public function removeUserMessage(\Geekhub\DreamBundle\Entity\Message $userMessages)
    {
        $this->userMessages->removeElement($userMessages);
    }

    /**
     * Get userMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserMessages()
    {
        return $this->userMessages;
    }

    /**
     * Set dream
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dream
     * @return User
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
     * Add favoriteDreams
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $favoriteDreams
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
     * Add dreams
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dreams
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
