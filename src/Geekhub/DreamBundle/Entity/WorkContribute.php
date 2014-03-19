<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:02
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
    protected $id;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamWorkContributions")
     */
    protected $dream;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hiddenContributor", type="boolean")
     */
    protected $hiddenContributor;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="workContributions")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="WorkResource")
     */
    protected $workResource;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "dream.not_blank")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="dream.only_numbers")
     * @ORM\Column(name="quantity", type="float")
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @Assert\NotBlank(message = "dream.not_blank")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="dream.only_numbers")
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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return $this
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
     * Set hiddenContributor
     *
     * @param  boolean        $hiddenContributor
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
     * @param  float          $quantity
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
     * @param  integer        $quantityDays
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
     * @param  \Geekhub\DreamBundle\Entity\Dream $dream
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
     * @param  \Geekhub\UserBundle\Entity\User $user
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
     * Set workResource
     *
     * @param  \Geekhub\DreamBundle\Entity\WorkResource $workResource
     * @return WorkContribute
     */
    public function setWorkResource(\Geekhub\DreamBundle\Entity\WorkResource $workResource = null)
    {
        $this->workResource = $workResource;

        return $this;
    }

    /**
     * Get workResource
     *
     * @return \Geekhub\DreamBundle\Entity\WorkResource
     */
    public function getWorkResource()
    {
        return $this->workResource;
    }
}
