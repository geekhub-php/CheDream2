<?php

namespace Geekhub\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Geekhub\DreamBundle\Entity\Status;

class UsersRepository extends EntityRepository
{
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
