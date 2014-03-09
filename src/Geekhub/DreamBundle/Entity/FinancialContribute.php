<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 13:30
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="financial_contributes")
 * @ORM\Entity()
 */
class FinancialContribute
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
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamFinancialContributions")
     */
    protected  $dream;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected  $hiddenContributor;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="financialContributions")
     */
    protected  $user;

    /**
     * @ORM\ManyToOne(targetEntity="FinancialResource")
     */
    protected  $financialArticle;

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
     * @return FinancialContribute
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
     * @return FinancialContribute
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
     * @return FinancialContribute
     */
    public function setDream(\Geekhub\DreamBundle\Entity\Dream $dream = null)
    {
        $this->dream = $dream;
        $dream->addDreamFinancialContribution($this);

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
     * @return FinancialContribute
     */
    public function setUser(\Geekhub\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
        $user->addFinancialContribution($this);

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
     * Set financialArticle
     *
     * @param \Geekhub\DreamBundle\Entity\FinancialResource $financialArticle
     * @return FinancialContribute
     */
    public function setFinancialArticle(\Geekhub\DreamBundle\Entity\FinancialResource $financialArticle = null)
    {
        $this->financialArticle = $financialArticle;

        return $this;
    }

    /**
     * Get financialArticle
     *
     * @return \Geekhub\DreamBundle\Entity\FinancialResource 
     */
    public function getFinancialArticle()
    {
        return $this->financialArticle;
    }
}
