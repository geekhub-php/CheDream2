<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 09.03.14
 * Time: 14:02
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name="work_contributes")
 * @ORM\Entity()
 */
class WorkContribute extends AbstractContribute
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
     * @ORM\ManyToOne(targetEntity="WorkResource")
     */
    protected $workResource;

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
