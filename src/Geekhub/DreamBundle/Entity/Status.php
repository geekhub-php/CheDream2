<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity
 */
class Status
{
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
     * @return string
     *
     * @ORM\Column(name="title", type="string", length=30)
     */
    protected $title;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="statuses")
     */
    protected $dream;

    public function __construct($title)
    {
        $this->title = $title;
    }

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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Status
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
