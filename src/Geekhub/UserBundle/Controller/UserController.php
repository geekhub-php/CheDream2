<?php

namespace Geekhub\UserBundle\Controller;

use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\UserBundle\Form\UserType;
use Geekhub\UserBundle\Form\UserForUpdateContactsType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function editUserAction(Request $request)
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

    /**
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     */
    public function userViewAction($user)
    {
        if ($this->getUser() == $user) {
            $showHiddenContributedDreams = true;
            $userDreams = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findBy(array('author' => $user));
        }
        else {
            $showHiddenContributedDreams = false;
            $userDreams = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findUserApprovedDreams($user);
        }
        $contributedDreams = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findAllContributedDreams($user, $showHiddenContributedDreams);

        return $this->render('GeekhubUserBundle:User:view.html.twig', 
            array(
                'user' => $user, 
                'contributedDreams' => $contributedDreams,
                'userDreams' => $userDreams,
            ));
    }

    /**
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     * @View(TemplateVar="dreams")
     */
    public function userOwnedDreamsViewAction($user, $status = "any")
    {
        switch ($status){
            case "any":
                if ($this->getUser()==$user) {
                    return $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findBy(array('author' => $user));
                }
                else {
                    return $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findUserApprovedDreams($user);
                }
                break;
            case "projects":
                if ($this->getUser()==$user) {
                    return $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findMyDreamProjects($user);
                }
                else {
                    return $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findUserDreamProjects($user);
                }
                break;
            default:
                return  $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findUserImplementedDreams($user);
        }
    }

    public function loginAction()
    {
        return $this->render('GeekhubUserBundle:User:login.html.twig');
    }

    /**
     * @param Request $request
     * @View(template="GeekhubUserBundle:User:userUpdateContacts.html.twig")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateContactsAction(Request $request)
    {
        $userAuth = $this->getUser();
        if (!$userAuth) {
            return $this->redirect($this->generateUrl('_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeekhubUserBundle:User')->findOneById($userAuth->getId());
        $user->setEmail('');

        $form = $this->CreateForm(new UserForUpdateContactsType(), $user, array(
                     'user' => $user,
                     'media-manager' => $this->container->get('sonata.media.manager.media'),
                     ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $hasUser = $em->getRepository('GeekhubUserBundle:User')
                ->findOneBy(array(
                    'email' => trim($form->get('email')->getData())
                ))
            ;

            if ($hasUser == null) {
                $em->flush();
                $this->sendEmail($user);

                return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
            } else {
                $this->container->get('session')->getFlashBag()->add(
                    'emailIsBusy',
                    $hasUser->getFirstName()." ".$hasUser->getLastName()." use this email (<a href='"
                    .$this->container->get('router')->generate('profile_update_contacts',
                        ['mergeUserWithEmail' => $hasUser->getEmail()])
                    ."'>email</a>)."
                );

//                $this->get('hwi_oauth.resource_owner.facebook')
            }
        }

        return array(
            'form'=>$form->createView(),
            'user'=>$user,
            'avatar'=>$user->getAvatar()
        );
    }

    /**
     * @param User $user
     */
    protected function sendEmail(User $user)
    {
        $dispatcher = $this->container->get('hip_mandrill.dispatcher');

        $message = new Message();
        $body = $this->container->get('templating')->render(
            'GeekhubResourceBundle:Email:registration.html.twig',
            array(
                'user' => $user
            )
        );

        $message->setFromEmail('test@gmail.com')
            ->setFromName('Черкаська мрія')
            ->addTo($user->getEmail())
            ->setSubject('REGISTRATION')
            ->setHtml($body)
        ;

        $dispatcher->send($message);
    }

    public function mergeAccountsByEmailAction($email)
    {
        return new Response('mergeAccountsByEmailAction');
    }
}
