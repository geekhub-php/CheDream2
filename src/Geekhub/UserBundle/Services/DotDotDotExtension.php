<?php

namespace Geekhub\UserBundle\Services;

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
        if (mb_strlen($text)>$number) {
            $newText=mb_substr($text,0,$number);
            $newText=$newText."...";

            return $newText;

        }
        else {

            return $text;
        }
    }

    public function getName()
    {
        return 'chabanenk0_twig_extension';
    }
}
