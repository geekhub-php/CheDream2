<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 02.03.14
 * Time: 14:55
 */

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DreamResource
 *
 * @ORM\Table(name="financial_resource")
 * @ORM\Entity
 */
class FinancialResource extends AbstractResource
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
