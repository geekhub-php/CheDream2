<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:02
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="work_contributes")
 * @ORM\Entity()
 */
class WorkContribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamWorkContributions")
     */
    protected  $dream;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected  $hiddenContributor;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="workContributions")
     */
    protected  $user;

    /**
     * @ORM\ManyToOne(targetEntity="WorkResource")
     */
    protected  $workArticle;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantityDays", type="integer")
     */
    protected $quantityDays;


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
     * Set hiddenContributor
     *
     * @param boolean $hiddenContributor
     * @return WorkContribute
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
     * Set quantity
     *
     * @param float $quantity
     * @return WorkContribute
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
     * Set quantityDays
     *
     * @param integer $quantityDays
     * @return WorkContribute
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
     * Set dream
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dream
     * @return WorkContribute
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
     * @return WorkContribute
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

    /**
     * Set workArticle
     *
     * @param \Geekhub\DreamBundle\Entity\WorkResource $workArticle
     * @return WorkContribute
     */
    public function setWorkArticle(\Geekhub\DreamBundle\Entity\WorkResource $workArticle = null)
    {
        $this->workArticle = $workArticle;

        return $this;
    }

    /**
     * Get workArticle
     *
     * @return \Geekhub\DreamBundle\Entity\WorkResource 
     */
    public function getWorkArticle()
    {
        return $this->workArticle;
    }
}
