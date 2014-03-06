<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.03.14
 * Time: 17:00
 */

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekhubDreamBundle:FAQ:index.html.twig');
    }
} 