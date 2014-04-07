<?php

namespace Geekhub\ResourceBundle\Twig;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class DotDotDotExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dotdotdot', array($this, 'dotdotdotFilter')),
        );
    }

    public function dotdotdotFilter($text, $number = 20)
    {
        $decodedText=iconv( mb_detect_encoding($text, mb_detect_order(), true), 'windows-1251', $text);

        if (mb_strlen($decodedText)>$number) {
            $newText=mb_substr($decodedText,0,$number);
            $newText=iconv("windows-1251", "UTF-8", $newText);
            $newText=$newText."...";

            return $newText;
        }
        else {

            return $text;
        }
    }

    public function getName()
    {
        return 'geekhub_twig_extension';
    }
}
 