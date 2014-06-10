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

    public function finContribute(User $user, Dream $dream, $security='nobody')
    {
        $finContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showFinancialContributors($user, $dream);

        $str = '';
        if($security == 'admin' or $security == 'owner') {
            foreach ($finContr as $fin) {
                $str .= '<li><span data-idResurce="'.$fin['finResurceId'].'" data-idUser="'.$user->getId().'" data-idDream="'.$dream->getId().'" data-contributeType="financial" class="remove" title="видалити"> видалити</span>'.$fin['resource'].' '.$fin['totalSum'].' грн.</li>';
            }
        } else {
            foreach ($finContr as $fin) {
                $str .= '<li>'.$fin['resource'].' '.$fin['totalSum'].' грн.</li>';
            }
        }

        return $str;
    }

    public function displayLimitWord($text, $limit = 20, $dot=false)
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

        return $dot ? $strResult : $strResult.'...';
    }

    public function equipContribute(User $user, Dream $dream, $security='nobody')
    {
        $equipContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showEquipmentContributors($user, $dream);

        $str = '';
        if($security == 'admin' or $security == 'owner') {
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
                $str .= '<li><span data-idResurce="'.$equip['equipResurceId'].'" data-idUser="'.$user->getId().'" data-idDream="'.$dream->getId().'" data-contributeType="equipment" class="remove" title="видалити"> видалити</span>'.$equip['resource'].' '.$equip['totalSum'].' '.$qType.'</li>';
            }
        } else {
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
        }

        return $str;
    }

    public function workContribute(User $user, Dream $dream, $security='nobody')
    {
        $workContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showWorkContributors($user, $dream);

        $str = '';
        if($security == 'admin' or $security == 'owner') {
            foreach ($workContr as $work) {
                $str .= '<li><span data-idResurce="'.$work['workResurceId'].'" data-idUser="'.$user->getId().'" data-idDream="'.$dream->getId().'" data-contributeType="work" class="remove" title="видалити"> видалити</span>'.$work['resource'].' '.$work['totalSum'].' дн.</li>';
            }
        } else {
            foreach ($workContr as $work) {
                $str .= '<li>'.$work['resource'].' '.$work['totalSum'].' дн.</li>';
            }
        }

        return $str;
    }

    public function otherContribute(User $user, Dream $dream, $security='nobody')
    {
        $otherContribute = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showOtherContributors($user, $dream);

        $str = '';
        if($security == 'admin' or $security == 'owner') {
            foreach ($otherContribute as $other) {
                $str .= '<li><span data-idResurce="'.$other['otherContrId'].'" data-idUser="'.$user->getId().'" data-idDream="'.$dream->getId().'" data-contributeType="other" class="remove" title="видалити"> видалити</span>'.$other['title'].'</li>';
            }
        } else {
            foreach ($otherContribute as $other) {
                $str .= '<li>'.$other['title'].'</li>';
            }
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
        if (count($dream->getDreamFinancialResources()) == 0) {

            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamFinancialResources()->map($this->getQuantity())->toArray();
        $financialResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamFinancialContributions()->map($this->getQuantity())->toArray();
        $financialContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($financialResourcesSum, $financialContributionsSum);
    }

    public function showPercentOfCompletionEquipment(Dream $dream)
    {
        if (count($dream->getDreamEquipmentResources()) == 0) {

            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamEquipmentResources()->map($this->getQuantity())->toArray();
        $equipmentResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamEquipmentContributions()->map($this->getQuantity())->toArray();
        $equipmentContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($equipmentResourcesSum, $equipmentContributionsSum);
    }

    public function showPercentOfCompletionWork(Dream $dream)
    {
        if (count($dream->getDreamWorkResources()) == 0) {

            return null;
        }

        $arrayResourcesQuantity = $dream->getDreamWorkResources()->map($this->getQuantity())->toArray();
        $workResourcesSum = array_sum($arrayResourcesQuantity);

        $arrayContributionsQuantity = $dream->getDreamWorkContributions()->map($this->getQuantity())->toArray();
        $workContributionsSum = array_sum($arrayContributionsQuantity);

        return $this->arithmeticMeanInPercent($workResourcesSum, $workContributionsSum);
    }

    private function getQuantity()
    {
        return function ($element) { return $element->getQuantity(); };
    }

    private function arithmeticMeanInPercent($resourceSum, $contributeSum)
    {
       return round($contributeSum * 100 / $resourceSum);
    }
}
