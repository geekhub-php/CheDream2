<?php

namespace Geekhub\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MergeRequest
 *
 * @ORM\Entity
 * @ORM\Table(name="merge_requests")
 */
class MergeRequest
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
     * @ORM\Column(name="proposersId", type="integer")
     */
    protected $proposersId;

    /**
     * @ORM\Column(name="mergingUserId", type="integer")
     */
    protected $mergingUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=32)
     */
    protected $hash;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getMergingUserId()
    {
        return $this->mergingUserId;
    }

    /**
     * @param mixed $mergingUser
     */
    public function setMergingUserId($mergingUserId)
    {
        $this->mergingUserId = $mergingUserId;
    }

    /**
     * @return mixed
     */
    public function getProposersId()
    {
        return $this->proposersId;
    }

    /**
     * @param mixed $proposer
     */
    public function setProposersId($proposersId)
    {
        $this->proposersId = $proposersId;
    }
}
