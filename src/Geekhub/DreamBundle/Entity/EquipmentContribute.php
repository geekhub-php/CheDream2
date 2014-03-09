<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 13:52
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="equipment_contributes")
 * @ORM\Entity()
 */
class EquipmentContribute
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
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamEquipmentContributions")
     */
    protected  $dream;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected  $hiddenContributor;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="equipmentContributions")
     */
    protected  $user;

    /**
     * @ORM\ManyToOne(targetEntity="EquipmentResource")
     */
    protected  $equipmentArticle;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

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
     * @return EquipmentContribute
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
     * @return EquipmentContribute
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
     * Set dream
     *
     * @param \Geekhub\DreamBundle\Entity\Dream $dream
     * @return EquipmentContribute
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
     * @return EquipmentContribute
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
     * Set equipmentArticle
     *
     * @param \Geekhub\DreamBundle\Entity\EquipmentResource $equipmentArticle
     * @return EquipmentContribute
     */
    public function setEquipmentArticle(\Geekhub\DreamBundle\Entity\EquipmentResource $equipmentArticle = null)
    {
        $this->equipmentArticle = $equipmentArticle;

        return $this;
    }

    /**
     * Get equipmentArticle
     *
     * @return \Geekhub\DreamBundle\Entity\EquipmentResource 
     */
    public function getEquipmentArticle()
    {
        return $this->equipmentArticle;
    }
}
