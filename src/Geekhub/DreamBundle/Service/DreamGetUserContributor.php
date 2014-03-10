<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 10.03.14
 * Time: 22:44
 */

namespace Geekhub\DreamBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\FinancialContribute;

class DreamGetUserContributor
{
    protected $dream;
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setDream(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function getUsersContribute()
    {
        $finUsers = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->getFinContributionUsers($this->dream);

        $equipUsers = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->getEquipContributionUsers($this->dream);

        $workUsers = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->getWorkContributionUsers($this->dream);

        $finUserArray = array();
        $equipUserArray = array();
        $workUserArray = array();

        foreach ($finUsers as $fin)
        {
            $finUserArray[] = $fin->getUser();
        }
        foreach ($equipUsers as $equip)
        {
            $equipUserArray[] = $equip->getUser();
        }
        foreach ($workUsers as $work)
        {
            $workUserArray[] = $work->getUser();
        }

        $userObjArray = array();

        $userObjArray = array_unique(array_merge($finUserArray, $equipUserArray, $workUserArray));

    return $userObjArray;
    }
}
