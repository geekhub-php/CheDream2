<?php

namespace Geekhub\UserBundle\Entity;

trait ContactsInfo
{

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=15, nullable=true)
     */
    protected $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookUrl", type="string", length=150, nullable=true)
     */
    protected $facebookUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="vkontakteUrl", type="string", length=150, nullable=true)
     */
    protected $vkontakteUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="odnoklassnikiUrl", type="string", length=150, nullable=true)
     */
    protected $odnoklassnikiUrl;

    /**
     * Set phone
     *
     * @param  string   $phone
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
     * Set skype
     *
     * @param  string   $skype
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
     * Set facebookUrl
     *
     * @param  string   $facebookUrl
     */
    public function setFacebookUrl($facebookUrl)
    {
        $this->facebookUrl = $facebookUrl;

        return $this;
    }

    /**
     * Get facebookUrl
     *
     * @return string
     */
    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }

    /**
     * Set vkontakteUrl
     *
     * @param  string   $vkontakteUrl
     */
    public function setVkontakteUrl($vkontakteUrl)
    {
        $this->vkontakteUrl = $vkontakteUrl;

        return $this;
    }

    /**
     * Get vkontakteUrl
     *
     * @return string
     */
    public function getVkontakteUrl()
    {
        return $this->vkontakteUrl;
    }

    /**
     * Set odnoklassnikiUrl
     *
     * @param  string   $odnoklassnikiUrl
     */
    public function setOdnoklassnikiUrl($odnoklassnikiUrl)
    {
        $this->odnoklassnikiUrl = $odnoklassnikiUrl;

        return $this;
    }

    /**
     * Get vkontakteUrl
     *
     * @return string
     */
    public function getOdnoklassnikiUrl()
    {
        return $this->odnoklassnikiUrl;
    }
}
