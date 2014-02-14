<?php
/**
 * Created by PhpStorm.
 * File: TagRepository.php
 * User: Yuriy Tarnavskiy
 * Date: 14.02.14
 * Time: 17:43
 */

namespace Geekhub\TagBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function loadTags()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t.name FROM TagBundle:Tag t')
            ->getArrayResult();
    }

} 