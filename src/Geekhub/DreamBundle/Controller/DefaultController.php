<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        var_dump('Main page'); exit;
        return $this->render('GeekhubDreamBundle:Default:index.html.twig');
    }
}
