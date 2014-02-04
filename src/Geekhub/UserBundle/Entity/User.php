<?php

namespace Geekhub\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Geekhub\DreamBundle\Entity\Dream;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Geekhub\UserBundle\Entity\UsersRepository")
 */
class User extends BaseUser
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
     * @ORM\Column(name="firstName", type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middleName", type="string", length=50)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=50)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=100)
     */
    private $avatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text")
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=45)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="vk", type="string", length=45)
     */
    private $vk;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=45)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="odnoklasniki", type="string", length=45)
     */
    private $odnoklasniki;

    //!!!! commented!!!!
    /*
     * @ORM\ManyToMany(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="usersWhoFavorites")
     */
    protected  $favoriteDreams;

    //!!!! commented!!!!
    /*
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\DreamNeeding", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userNeedings;

    //!!!! commented!!!!
    /*
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userComments;

    //!!!! commented!!!!
    /*
     * @ORM\OneToMany(targetEntity="Geekhub\DreamBundle\Entity\Message", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $userMessages;


    //!!!! commented!!!!
    /*
     * @ORM\OneToOne(targetEntity="Geekhub\DreamBundle\Entity\Dream", mappedBy="user")
     */
    protected  $dream;

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
}
