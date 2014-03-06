<?php

namespace Geekhub\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Geekhub\UserBundle\Entity\Contacts;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GeekhubUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
