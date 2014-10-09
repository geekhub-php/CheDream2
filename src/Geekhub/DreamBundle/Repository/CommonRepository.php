<?php

namespace Geekhub\DreamBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommonRepository extends EntityRepository
{
    public function findByPeriod(\DateTime $from, \DateTime $to)
    {
        $from->setTime(0, 0);
        $to->setTime(23, 59);

        $query = $this->createQueryBuilder('s')
            ->where('s.createdAt >= :from')
            ->andWhere('s.createdAt <= :to ')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
        ;

        return $statuses = $query->getResult();
    }
}
