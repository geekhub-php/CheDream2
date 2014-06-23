<?php

namespace Geekhub\ResourceBundle\Twig;

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
        $decodedText = trim(strip_tags($text));
        if (mb_strlen($decodedText, 'UTF-8')>$number) {
            $newText=mb_substr($decodedText,0,$number, 'UTF-8');
            $newText=$newText."...";

            return $newText;
        } else {
            return trim(strip_tags($text));
        }
    }

    public function getName()
    {
        return 'geekhub_twig_extension';
    }
}
