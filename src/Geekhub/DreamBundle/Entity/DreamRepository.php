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

	public function findNewDreams($limit, $offset)
    {
        $em = $this->getEntityManager();
       $query = $em->createQuery(
            'SELECT d FROM GeekhubDreamBundle:Dream d
            ORDER BY d.createdAt'
        )->setMaxResults($limit)
         ->setFirstResult($offset);

        return $query->getResult();
    }

	public function findPopularDreams($limit, $offset)
    {
        $em = $this->getEntityManager();
       $query = $em->createQuery(
            'SELECT d
            FROM GeekhubDreamBundle:Dream d
            ORDER BY d.createdAt desc'
        )->setMaxResults($limit)
         ->setFirstResult($offset);

        return $query->getResult(); //??? doesnt work
    }
    public function findLimitedDreamsByStatus($status, $limit, $offset)
    {
        $em = $this->getEntityManager();
       $query = $em->createQuery(
            'SELECT d FROM GeekhubDreamBundle:Dream d
            WHERE d.currentStatus = :status
            ORDER BY d.createdAt'
        )->setMaxResults($limit)
         ->setFirstResult($offset)
         ->setParameter('status', $status);

        return $query->getResult();
    }

    public function  getDreamsByStatus($status)
    {

        return $this->getEntityManager()->getRepository('GeekhubDreamBundle:Dream')->findBy(array(
            'currentStatus' => $status
        ));
    }

    public function getSliceDreamsByStatus($status, $limit, $offset)
    {

        return $this->getEntityManager()
            ->createQuery('SELECT d
                           FROM GeekhubDreamBundle:Dream d
                           where d.currentStatus = :status
                           order by d.id
                           ')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter('status', $status)
            ->getResult();
    }

    public function getCountContributorsByDream(Dream $dream)
    {
        return count($this->getArrayContributorsByDream($dream));
    }

    public function getArrayContributorsByDream(Dream $dream)
    {
        return array_unique(array_merge(
            $this->getEquipmentContributors($dream)->toArray(),
            $this->getFinancialContributors($dream)->toArray(),
            $this->getWorkContributors($dream)->toArray(),
            $this->getOtherContributors($dream)->toArray()
        ));
    }

    public function getFinancialContributors(Dream $dream)
    {
        return $dream->getDreamFinancialContributions()->map($this->getUser());
    }

    public function getEquipmentContributors(Dream $dream)
    {
        return $dream->getDreamEquipmentContributions()->map($this->getUser());
    }

    public function getWorkContributors(Dream $dream)
    {
        return $dream->getDreamWorkContributions()->map($this->getUser());
    }

    public function getOtherContributors(Dream $dream)
    {
        return $dream->getDreamOtherContributions()->map($this->getUser());
    }

    public function showFinancialContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum
                           FROM GeekhubDreamBundle:FinancialContribute c
                           join c.financialResource f
                           where c.hiddenContributor = 0 and c.user = :user and c.dream = :dream
                           group by f.title
                           order by f.title
                           ')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showEquipmentContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum, f.quantityType as qType
                           FROM GeekhubDreamBundle:EquipmentContribute c
                           join c.equipmentResource f
                           where c.hiddenContributor = 0 and c.user = :user and c.dream = :dream
                           group by f.title
                           order by f.title
                           ')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showWorkContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum, sum(c.quantityDays) as totalDays
                           FROM GeekhubDreamBundle:WorkContribute c
                           join c.workResource f
                           where c.hiddenContributor = 0 and c.user = :user and c.dream = :dream
                           group by f.title
                           order by f.title
                           ')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showOtherContributors($user, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c.title as title
                           FROM GeekhubDreamBundle:OtherContribute c
                           where c.user = :user and c.dream = :dream
                           order by c.id
                           ')
            ->setParameter('user', $user)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showSumFinancialResource($financial, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT sum(c.quantity) as totalSum
                           FROM GeekhubDreamBundle:FinancialContribute c
                           join c.financialResource f
                           where c.financialResource = :financial and c.dream = :dream
                           group by f.title
                           ')
            ->setParameter('financial', $financial)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showSumEquipmentResource($equipment, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT sum(c.quantity) as totalSum
                           FROM GeekhubDreamBundle:EquipmentContribute c
                           join c.equipmentResource f
                           where c.equipmentResource = :equipment and c.dream = :dream
                           group by f.title
                           ')
            ->setParameter('equipment', $equipment)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function showSumWorkResource($work, $dream)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT sum(c.quantity) as totalSum, sum(c.quantityDays) as totalDays
                           FROM GeekhubDreamBundle:WorkContribute c
                           join c.workResource f
                           where c.workResource = :work and c.dream = :dream
                           group by f.title
                           ')
            ->setParameter('work', $work)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    protected function getUser()
    {
        return function ($contribute) {return $contribute->getUser(); };
    }
}
