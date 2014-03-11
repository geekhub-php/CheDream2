<?php

namespace Geekhub\DreamBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DreamsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DreamRepository extends EntityRepository
{
    public function getFinContributionUsers($dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u, c from GeekhubDreamBundle:FinancialContribute c
                           join c.user u
                           where c.dream = ?1
                           group by u
                           ')
            ->setParameter(1, $dream)
            ->getResult();
    }

    public function getEquipContributionUsers($dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u, c from GeekhubDreamBundle:EquipmentContribute c
                           join c.user u
                           where c.dream = ?1
                           group by u
                           ')
            ->setParameter(1, $dream)
            ->getResult();
    }

    public function getWorkContributionUsers($dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u, c from GeekhubDreamBundle:WorkContribute c
                           join c.user u
                           where c.dream = ?1
                           group by u
                           ')
            ->setParameter(1, $dream)
            ->getResult();
    }

    public function showFinancialContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as article, sum(c.quantity) as totalSum
                           FROM GeekhubDreamBundle:FinancialContribute c
                           join c.financialArticle f
                           where c.hiddenContributor = 0 and c.user = ?1 and c.dream = ?2
                           group by f.title
                           order by f.title
                           ')
            ->setParameter(1, $user)
            ->setParameter(2, $dream)
            ->getResult();
    }

    public function showEquipmentContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as article, sum(c.quantity) as totalSum, f.quantityType as qType
                           FROM GeekhubDreamBundle:EquipmentContribute c
                           join c.equipmentArticle f
                           where c.hiddenContributor = 0 and c.user = ?1 and c.dream = ?2
                           group by f.title
                           order by f.title
                           ')
            ->setParameter(1, $user)
            ->setParameter(2, $dream)
            ->getResult();
    }

    public function showWorkContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as article, sum(c.quantity) as totalSum, sum(c.quantityDays) as totalDays
                           FROM GeekhubDreamBundle:WorkContribute c
                           join c.workArticle f
                           where c.hiddenContributor = 0 and c.user = ?1 and c.dream = ?2
                           group by f.title
                           order by f.title
                           ')
            ->setParameter(1, $user)
            ->setParameter(2, $dream)
            ->getResult();
    }
}
