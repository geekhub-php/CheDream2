<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 10.03.14
 * Time: 22:27
 */

namespace Geekhub\DreamBundle\Twig;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Geekhub\DreamBundle\Entity\AbstractContributeResource;

class ContributionExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getName()
    {
        return 'contribution_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('finContribute', array($this, 'finContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('equipContribute', array($this, 'equipContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('workContribute', array($this, 'workContribute'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('finResource', array($this, 'finResource'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('equipResource', array($this, 'equipResource'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('workResource', array($this, 'workResource'), array('is_safe' => array('html'))),
        );
    }

    public function finContribute($user, $dream)
    {
        $finContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showFinancialContributors($user, $dream);

        $str = '';
        foreach ($finContr as $fin)
        {
            $str .= '<li>'.$fin['article'].' '.$fin['totalSum'].' грн.</li>';
        }
        return $str;
    }

    public function equipContribute($user, $dream)
    {
        $equipContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showEquipmentContributors($user, $dream);

        $str = '';
        foreach ($equipContr as $equip)
        {
            switch ($equip['qType']) {
                case AbstractContributeResource::KG:
                    $qType = 'кг.';
                    break;
                case AbstractContributeResource::PIECE:
                    $qType = 'шт.';
                    break;
                case AbstractContributeResource::TON:
                    $qType = 'тон';
                    break;
                default:
                    $qType = '';
            }
            $str .= '<li>'.$equip['article'].' '.$equip['totalSum'].' '.$qType.'</li>';
        }
        return $str;
    }

    public function workContribute($user, $dream)
    {
        $workContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showWorkContributors($user, $dream);

        $str = '';
        foreach ($workContr as $work)
        {
            $str .= '<li>'.$work['article'].' '.$work['totalSum'].' чол./ '.$work['totalDays'].' дн.</li>';
        }
        return $str;
    }

    public function finResource($financial, $dream)
    {
        $finResSumTotal = 0;
        $finResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumFinancialResource($financial, $dream);

        foreach ($finResSum as $fin)
        {
            $finResSumTotal = $fin['totalSum'];
        }

        return $finResSumTotal.' грн.';
    }

    public function equipResource($equipment, $dream)
    {
        $equipResSumTotal = 0;
        $equipResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumEquipmentResource($equipment, $dream);

        foreach ($equipResSum as $equip)
        {
            $equipResSumTotal = $equip['totalSum'];
        }

        return $equipResSumTotal;
    }

    public function workResource($work, $dream)
    {
        $str = 0;
        $workResSum = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showSumWorkResource($work, $dream);

        foreach ($workResSum as $work)
        {
            $str = $work['totalSum'].' чол./ '.$work['totalDays'].' дн.';
        }
        return $str;
    }
}
