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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
