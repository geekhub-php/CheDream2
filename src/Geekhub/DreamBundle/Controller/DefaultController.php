<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekhubDreamBundle:Default:index.html.twig');
    }

    public function contactAction()
    {
        return $this->render('GeekhubDreamBundle:Default:contact.html.twig');
    }
}
