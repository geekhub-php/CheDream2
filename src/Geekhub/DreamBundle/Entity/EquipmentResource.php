<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 02.03.14
 * Time: 15:05
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DreamResource
 *
 * @ORM\Table(name="equipment_resource")
 * @ORM\Entity
 */
class EquipmentResource extends AbstractContributeResource
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
     * @var string
     *
     * @ORM\Column(name="quantityType", type="string", length=15, nullable=true)
     */
    protected $quantityType;

    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamEquipmentResources")
     */
    private $dream;

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
     * Set dream
     *
     * @param  Dream         $dream
     * @return DreamResource
     */
    public function setDream(Dream $dream = null)
    {
        $this->dream = $dream;

        return $this;
    }

    /**
     * Get dream
     *
     * @return Dream
     */
    public function getDream()
    {
        return $this->dream;
    }

    /**
     * Set quantityType
     *
     * @param  string        $quantityType
     * @return DreamResource
     */
    public function setQuantityType($quantityType)
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    /**
     * Get quantityType
     *
     * @return string
     */
    public function getQuantityType()
    {
        return $this->quantityType;
    }
}
