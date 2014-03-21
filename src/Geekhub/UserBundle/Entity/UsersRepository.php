<?php

namespace Geekhub\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class UsersRepository extends EntityRepository
{
    private function dreamWithNotHiddenContributionMerge($em, $user, $typeContributionEntityName, ArrayCollection $contributedDreams)
    {

        $query = $em->createQuery(
           'SELECT d, count(c) as count_c
            FROM GeekhubDreamBundle:Dream d 
            JOIN d.'.$typeContributionEntityName.' c
            WHERE c.user = :user and c.hiddenContributor = false'
        )->setParameter('user', $user);

        $newContributedDreams = $query->getResult();

        foreach ($newContributedDreams as $dream) {
            if ($dream['count_c'] > 0) {
                $contributedDreams->add($dream[0]);
            }
        }
        return $contributedDreams;

    }

    public function findAllContributedDreams($user)
    {
        $em = $this->getEntityManager();

        $contributedDreams = new ArrayCollection();
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'dreamFinancialContributions', $contributedDreams);
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'dreamEquipmentContributions', $contributedDreams);
        $contributedDreams = $this->dreamWithNotHiddenContributionMerge($em, $user, 'dreamWorkContributions', $contributedDreams);
        return $contributedDreams;
    }
}
