<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity()
 */
class Comment
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
     * @var string
     *
     * @Assert\NotBlank(message = "comment.text.not_blank")
     * @Assert\Length(min = "5", minMessage = "comment.text.length_error_short")
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Dream", inversedBy="dreamComments")
     */
    protected $dream;

    /**
     * @ORM\ManyToOne(targetEntity="Geekhub\UserBundle\Entity\User", inversedBy="userComments")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * Set text
     *
     * @param  string  $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * Add children
     *
     * @param  \Geekhub\DreamBundle\Entity\Comment $children
     * @return Comment
     */
    public function addChild(\Geekhub\DreamBundle\Entity\Comment $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Geekhub\DreamBundle\Entity\Comment $children
     */
    public function removeChild(\Geekhub\DreamBundle\Entity\Comment $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param  \Geekhub\DreamBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\Geekhub\DreamBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Geekhub\DreamBundle\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }
}
