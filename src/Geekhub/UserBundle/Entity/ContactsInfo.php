<?php

namespace Geekhub\UserBundle\Entity;

trait ContactsInfo
{

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=60, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=60, nullable=true)
     */
    protected $skype;

    /**
     * Set phone
     *
     * @param string $phone
     * @return $this
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
     * @param string $skype
     * @return $this
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
     * Get facebookUrl
     *
     * @return string
     */
    public function getFacebookUrl()
    {
        return sprintf('http://www.facebook.com/%d', $this->facebookId);
    }

    /**
     * Get vkontakteUrl
     *
     * @return string
     */
    public function getVkontakteUrl()
    {
        return sprintf('http://vk.com/id%d', $this->vkontakteId);
    }

    /**
     * Get odnoklassnikiUrl
     *
     * @return string
     */
    public function getOdnoklassnikiUrl()
    {
        return sprintf('http://www.odnoklassniki.ru/profile/%d', $this->odnoklassnikiId);
    }
}
