<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 02.03.14
 * Time: 15:07
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DreamResource
 *
 * @ORM\Table(name="work_resource")
 * @ORM\Entity
 */
class WorkResource extends AbstractResource
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @Assert\NotBlank(message = "dream.not_blank")
     * @Assert\Regex(pattern="/^[0-9.]+$/", message="dream.only_numbers")
     * @ORM\Column(name="quantityDays", type="integer", nullable=true)
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
     * @param  integer       $quantityDays
     * @return WorkResource
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
}
