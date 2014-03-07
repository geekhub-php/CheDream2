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

        $form = $this->CreateForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form -> isValid()) {
            // !!!Object type are compared by reference, not by value.
            // Doctrine updates this values if the reference changes
            $contacts =  new Contacts();
            $contacts->setPhone($user->getContacts()->getPhone());
            $contacts->setSkype($user->getContacts()->getSkype());
            $user->setContacts($contacts);
            if ($user->getAvatar()) {
                $mediaManager = $this->container->get('sonata.media.manager.media');
                $mediaManager->save($user->getAvatar());
            }

            $em->flush();

            return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
        }

        return $this->render("GeekhubUserBundle:User:user.html.twig",array('form'=>$form->createView(),'user'=>$user, 'avatar'=>$user->getAvatar()));
    }
}
