<?php

namespace Geekhub\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\DreamBundle\Entity\Status;

class UsersRepository extends EntityRepository
{
    private function dreamWithNotHiddenContributionMerge($em, $user, $typeContributionEntityName, ArrayCollection $contributedDreams, $showHiddenContributedDreams)
    {
        if ($showHiddenContributedDreams) {
            $conditions = array(
                'user'=>$user,
            );
        }
        else {
            $conditions = array(
                'user'=>$user,
                'hiddenContributor'=>false,
            );
        }

        $contributions = $em->getRepository('GeekhubDreamBundle:'.$typeContributionEntityName)->findBy($conditions);
        foreach ($contributions as $contribution) {
            if (!$contributedDreams->contains($contribution->getDream())) {
                $contributedDreams->add($contribution->getDream());
            }
        }

        return $contributedDreams;
    }

    public function findAllContributedDreams($user, $showHiddenContributedDreams)
    {
        $em = $this->getEntityManager();

        $contributedDreams = new ArrayCollection();
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'FinancialContribute', $contributedDreams, $showHiddenContributedDreams);
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'EquipmentContribute', $contributedDreams, $showHiddenContributedDreams);
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'WorkContribute', $contributedDreams, $showHiddenContributedDreams);
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'OtherContribute', $contributedDreams, $showHiddenContributedDreams);
        return $contributedDreams;
    }

    public function findMyDreamProjects($user)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
           "SELECT d
            FROM GeekhubDreamBundle:Dream d 
            WHERE d.author = :user and (d.currentStatus = :submitted
                                   or d.currentStatus = :rejected
                                   or d.currentStatus = :collectingResources
                                   or d.currentStatus = :implementing)"
        )->setParameter('user', $user)
         ->setParameter('submitted', Status::SUBMITTED)
         ->setParameter('rejected', Status::REJECTED)
         ->setParameter('collectingResources', Status::COLLECTING_RESOURCES)
         ->setParameter('implementing', Status::IMPLEMENTING);

        return $query->getResult();
    }

    public function findUserApprovedDreams($user)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
           "SELECT d
            FROM GeekhubDreamBundle:Dream d 
            WHERE d.author = :user and (d.currentStatus = :collectingResources
                                   or d.currentStatus = :implementing
                                   or d.currentStatus = :completed
                                   or d.currentStatus = :success
                                   or d.currentStatus = :fail)"
        )->setParameter('user', $user)
         ->setParameter('collectingResources', Status::COLLECTING_RESOURCES)
         ->setParameter('implementing', Status::IMPLEMENTING)
         ->setParameter('completed', Status::COMPLETED)
         ->setParameter('success', Status::SUCCESS)
         ->setParameter('fail', Status::FAIL);

        return $query->getResult();
    }


    public function findUserImplementedDreams($user)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
           "SELECT d
            FROM GeekhubDreamBundle:Dream d 
            WHERE d.author = :user and (d.currentStatus = :completed
                                   or d.currentStatus = :success
                                   or d.currentStatus = :fail)"
        )->setParameter('user', $user)
         ->setParameter('completed', Status::COMPLETED)
         ->setParameter('success', Status::SUCCESS)
         ->setParameter('fail', Status::FAIL);

        return $query->getResult();
    }

    public function findUserDreamProjects($user)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
           "SELECT d
            FROM GeekhubDreamBundle:Dream d 
            WHERE d.author = :user and (d.currentStatus = :collectingResources
                                     or d.currentStatus = :implementing)"
        )->setParameter('user', $user)
         ->setParameter('collectingResources', Status::COLLECTING_RESOURCES)
         ->setParameter('implementing', Status::IMPLEMENTING);

        return $query->getResult();
    }
}
