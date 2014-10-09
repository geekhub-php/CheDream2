<?php

namespace Geekhub\DreamBundle\Repository;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;

/**
 * DreamsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DreamRepository extends CommonRepository
{

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
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum, f.id as finResurceId
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
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum, f.quantityType as qType, f.id as equipResurceId
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
            ->createQuery('SELECT f.title as resource, sum(c.quantity) as totalSum, f.id as workResurceId
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
            ->createQuery('SELECT c.title as title, c.id as otherContrId
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
            ->createQuery('SELECT sum(c.quantity) as totalSum
                           FROM GeekhubDreamBundle:WorkContribute c
                           join c.workResource f
                           where c.workResource = :work and c.dream = :dream
                           group by f.title
                           ')
            ->setParameter('work', $work)
            ->setParameter('dream', $dream)
            ->getResult();
    }

    public function searchDreams($text)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d
                           FROM GeekhubDreamBundle:Dream d
                           where
                           ( d.title like :search_text or d.description like :search_text )
                           and
                           ( d.currentStatus = :status1 or d.currentStatus = :status2 or d.currentStatus = :status3 )
                           order by d.id
                           ')
            ->setParameter('search_text', '%'.$text.'%')
            ->setParameter('status1', Status::COLLECTING_RESOURCES)
            ->setParameter('status2', Status::IMPLEMENTING)
            ->setParameter('status3', Status::SUCCESS)
            ->getResult();
    }

    protected function getUser()
    {
        return function ($contribute) {return $contribute->getUser(); };
    }
}