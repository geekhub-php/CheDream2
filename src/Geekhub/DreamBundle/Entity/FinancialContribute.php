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
 * @ORM\Entity(repositoryClass="Geekhub\DreamBundle\Repository\CommonRepository")
 */
class FinancialContribute extends AbstractContribute
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
     * @ORM\ManyToOne(targetEntity="FinancialResource")
     */
    protected $financialResource;

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
     * Set financialResource
     *
     * @param  \Geekhub\DreamBundle\Entity\FinancialResource $financialResource
     * @return FinancialContribute
     */
    public function setFinancialResource(\Geekhub\DreamBundle\Entity\FinancialResource $financialResource = null)
    {
        $this->financialResource = $financialResource;

        return $this;
    }

    /**
     * Get financialResource
     *
     * @return \Geekhub\DreamBundle\Entity\FinancialResource
     */
    public function getFinancialResource()
    {
        return $this->financialResource;
    }
}
