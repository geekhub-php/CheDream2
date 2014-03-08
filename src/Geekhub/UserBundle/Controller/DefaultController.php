<?php

namespace Geekhub\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GeekhubUserBundle:Default:index.html.twig', array('name' => $name));
    }

    public function userViewAction($id)
    {
        $user = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findOneById($id);
        return $this->render('GeekhubUserBundle:User:view.html.twig', array('user' => $user));
    }

}
