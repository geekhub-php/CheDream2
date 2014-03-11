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
        );
    }


    public function finContribute($user, $dream)
    {
        $finContr = $this->doctrine->getManager()->getRepository('GeekhubDreamBundle:Dream')->showFinancialContributors($user, $dream);

        $str = '';
        foreach ($finContr as $fin)
        {
            $str .= '<li>'.$fin['article'].' '.$fin['totalSum'].'</li>';
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
}
