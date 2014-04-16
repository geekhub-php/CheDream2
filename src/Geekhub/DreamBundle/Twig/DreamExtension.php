<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 10.03.14
 * Time: 22:27
 */

namespace Geekhub\DreamBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentResource;
use Geekhub\DreamBundle\Entity\FinancialResource;
use Geekhub\DreamBundle\Entity\WorkResource;
use Geekhub\UserBundle\Entity\User;

class DreamExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getName()
    {
        return 'dream_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('finContribute', array($this, 'finContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('equipContribute', array($this, 'equipContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('workContribute', array($this, 'workContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('otherContribute', array($this, 'otherContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('finResource', array($this, 'finResource'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('equipResource', array($this, 'equipResource'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('workResource', array($this, 'workResource'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('getCountContributors', array($this, 'getCountContributors'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('displayLimitWord', array($this, 'displayLimitWord'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('showPercentOfCompletionFinancial', array($this, 'showPercentOfCompletionFinancial'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('showPercentOfCompletionEquipment', array($this, 'showPercentOfCompletionEquipment'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('showPercentOfCompletionWork', array($this, 'showPercentOfCompletionWork'), array('is_safe' => array('html'))),
        );
    }

    public function getCountContributors(Dream $dream)
    {
        return $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->getCountContributorsByDream($dream);
    }

    public function finContribute(User $user, Dream $dream)
    {
        $finContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showFinancialContributors($user, $dream);

        $str = '';
        foreach ($finContr as $fin) {
            $str .= '<li>'.$fin['resource'].' '.$fin['totalSum'].' грн.</li>';
        }

        return $str;
    }

    public function displayLimitWord($text, $limit = 20)
    {
        $words = explode(' ', strip_tags(trim($text)));
        $countWords = count($words);

        if ($countWords < $limit) {
            $lim = $countWords;
        } else {
            $lim = $limit;
        }

        $strResult = '';
        for ($i = 0; $i < $lim; $i++) {
            $strResult .= $words[$i].' ';
        }

        return $strResult.'...';
    }

    public function equipContribute(User $user, Dream $dream)
    {
        $equipContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showEquipmentContributors($user, $dream);

        $str = '';
        foreach ($equipContr as $equip) {
            switch ($equip['qType']) {
                case EquipmentResource::KG:
                    $qType = 'кг.';
                    break;
                case EquipmentResource::PIECE:
                    $qType = 'шт.';
                    break;
                case EquipmentResource::TON:
                    $qType = 'тон';
                    break;
                default:
                    $qType = '';
            }
            $str .= '<li>'.$equip['resource'].' '.$equip['totalSum'].' '.$qType.'</li>';
        }

        return $str;
    }

    public function workContribute(User $user, Dream $dream)
    {
        $workContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showWorkContributors($user, $dream);

        $str = '';
        foreach ($workContr as $work) {
            $str .= '<li>'.$work['resource'].' '.$work['totalSum'].' дн.</li>';
        }

        return $str;
    }

    public function otherContribute(User $user, Dream $dream)
    {
        $otherContribute = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showOtherContributors($user, $dream);

        $str = '';
        foreach ($otherContribute as $other) {
            $str .= '<li>'.$other['title'].'</li>';
        }

        return $str;
    }

    public function finResource(FinancialResource $financial,Dream $dream)
    {
        $finResSumTotal = 0;
        $finResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumFinancialResource($financial, $dream);

        foreach ($finResSum as $fin) {
            $finResSumTotal = $fin['totalSum'];
        }

        return $finResSumTotal;
    }

    public function equipResource(EquipmentResource $equipment, Dream $dream)
    {
        $equipResSumTotal = 0;
        $equipResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumEquipmentResource($equipment, $dream);

        foreach ($equipResSum as $equip) {
            $equipResSumTotal = $equip['totalSum'];
        }

        return $equipResSumTotal;
    }

    public function workResource(WorkResource $work, Dream $dream)
    {
        $str = 0;
        $workResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumWorkResource($work, $dream);

        foreach ($workResSum as $work) {
            $str = $work['totalSum'];
        }

        return $str;
    }

    public function showPercentOfCompletionFinancial(Dream $dream)
    {
        $financialResources = $dream->getDreamFinancialResources();
        /** @var \Geekhub\DreamBundle\Entity\FinancialResource $financialResource */
        $financialResourcesSum = 0;
        foreach ($financialResources as $financialResource) {
            $financialResourcesSum += $financialResource->getQuantity();
        }

        $financialContributions = $dream->getDreamFinancialContributions();
        /** @var \Geekhub\DreamBundle\Entity\FinancialContribute $financialContribute */
        $financialContributionsSum = 0;
        foreach ($financialContributions as $financialContribute) {
            $financialContributionsSum += $financialContribute->getQuantity();
        }

        return $this->arithmeticMeanInPercent($financialResourcesSum, $financialContributionsSum);
    }

    public function showPercentOfCompletionEquipment(Dream $dream)
    {
        $equipmentResources = $dream->getDreamEquipmentResources();
        /** @var \Geekhub\DreamBundle\Entity\EquipmentResource $equipmentResource */
        $equipmentResourcesSum = 0;
        foreach ($equipmentResources as $equipmentResource) {
            $equipmentResourcesSum += $equipmentResource->getQuantity();
        }

        $equipmentContributions = $dream->getDreamEquipmentContributions();
        /** @var \Geekhub\DreamBundle\Entity\EquipmentContribute $equipmentContribute */
        $equipmentContributionsSum = 0;
        foreach ($equipmentContributions as $equipmentContribute) {
            $equipmentContributionsSum += $equipmentContribute->getQuantity();
        }

        return $this->arithmeticMeanInPercent($equipmentResourcesSum, $equipmentContributionsSum);
    }

    public function showPercentOfCompletionWork(Dream $dream)
    {
        $workResources = $dream->getDreamWorkResources();
        /** @var \Geekhub\DreamBundle\Entity\WorkResource $workResource */
        $workResourcesSum = 0;
        foreach ($workResources as $workResource) {
            $workResourcesSum += $workResource->getQuantity();
        }

        $workContributions = $dream->getDreamWorkContributions();
        /** @var \Geekhub\DreamBundle\Entity\WorkContribute $workContribute */
        $workContributionsSum = 0;
        foreach ($workContributions as $workContribute) {
            $workContributionsSum += $workContribute->getQuantity();
        }

        return $this->arithmeticMeanInPercent($workResourcesSum, $workContributionsSum);
    }

    private function arithmeticMeanInPercent($resourceSum, $contributeSum)
    {
       return round($contributeSum * 100 / $resourceSum);
    }
}
