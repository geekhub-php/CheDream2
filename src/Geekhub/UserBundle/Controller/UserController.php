<?php

namespace Geekhub\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Geekhub\UserBundle\Entity\Contacts;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GeekhubUserBundle:Default:index.html.twig', array('name' => $name));
    }

    public function userEditAction(Request $request)
    {
        $userAuth=$this->getUser();
        if (!$userAuth) {
            return $this->redirect($this->generateUrl('_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeekhubUserBundle:User')->findOneById($userAuth->getId());

        $form = $this->CreateForm(new UserType(), $user, array(
                     'user' => $user,
                     'media-manager' => $this->container->get('sonata.media.manager.media'),
                     ));

        $form->handleRequest($request);

        if ($form -> isValid()) {

            $em->flush();

            return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
        }

        return $this->render("GeekhubUserBundle:User:user.html.twig",array('form'=>$form->createView(),'user'=>$user, 'avatar'=>$user->getAvatar()));
    }

    public function userViewAction($id)
    {
        $user = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findOneById($id);
        //$contributedDreams1 = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findByContributedUser($id);
        
        $contributedDreams = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findAllContributedDreams($user);

        return $this->render('GeekhubUserBundle:User:view.html.twig', array('user' => $user, 'contributedDreams' => $contributedDreams));
    }

    public function loginAction()
    {
        return $this->render('GeekhubUserBundle:User:login.html.twig');
    }

}
