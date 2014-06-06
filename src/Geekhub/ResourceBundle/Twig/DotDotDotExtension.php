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
        $decodedText=iconv( mb_detect_encoding($text, mb_detect_order(), true), 'windows-1251//IGNORE', $text);

        if (mb_strlen($decodedText)>$number) {
            $newText=mb_substr($decodedText,0,$number);
            $newText=iconv("windows-1251", "utf-8//IGNORE", $newText);
            $newText=$newText."...";

            return $newText;
        } else {
            return $text;
        }
    }

    public function getName()
    {
        return 'geekhub_twig_extension';
    }
}
