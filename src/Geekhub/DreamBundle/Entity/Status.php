<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Geekhub\ResourceBundle\Entity\Timestampable;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity
 */
class Status
{
    use Timestampable;

    const SUBMITTED            = 'submitted';
    const REJECTED             = 'rejected';
    const COLLECTING_RESOURCES = 'collecting-resources';
    const IMPLEMENTING         = 'implementing';
    const COMPLETED            = 'completed';
    const SUCCESS              = 'success';
    const FAIL                 = 'fail';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=30)
     */
    protected $title;

    /**
     * @ORM\OneToOne(targetEntity="Dream", inversedBy="status")
     * @ORM\JoinColumn(name="dream_id", referencedColumnName="id")
     */
    protected $dream;

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
     * Set title
     *
     * @param  string $title
     * @return Status
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return integer
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dream
     *
     * @param  \Geekhub\DreamBundle\Entity\Dream $dream
     * @return Status
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
}
