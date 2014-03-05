<?php

namespace Geekhub\TagBundle\Entity;

use \FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table(name="tags")
* @ORM\Entity()
*/
class Tag extends BaseTag
{
    /**
    * @var integer $id
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
    * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
    **/
    protected $tagging;

}
