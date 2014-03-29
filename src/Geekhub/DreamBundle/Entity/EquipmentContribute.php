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
class EquipmentContribute extends AbstractContribute
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
     * @ORM\ManyToOne(targetEntity="EquipmentResource")
     */
    protected $equipmentResource;

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
     * Set equipmentResource
     *
     * @param  \Geekhub\DreamBundle\Entity\EquipmentResource $equipmentResource
     * @return EquipmentContribute
     */
    public function setEquipmentResource(\Geekhub\DreamBundle\Entity\EquipmentResource $equipmentResource = null)
    {
        $this->equipmentResource = $equipmentResource;

        return $this;
    }

    /**
     * Get equipmentResource
     *
     * @return \Geekhub\DreamBundle\Entity\EquipmentResource
     */
    public function getEquipmentResource()
    {
        return $this->equipmentResource;
    }
}
