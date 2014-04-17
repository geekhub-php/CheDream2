<?php

namespace Geekhub\UserBundle\Controller;

use Geekhub\UserBundle\Entity\MergeRequest;
use Geekhub\UserBundle\Entity\User;
use Hip\MandrillBundle\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\UserBundle\Form\UserType;
use Geekhub\UserBundle\Form\UserForUpdateContactsType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;

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

    public function updateContactsAction(Request $request)
    {
        $userAuth=$this->getUser();
        if (!$userAuth) {
            return $this->redirect($this->generateUrl('_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GeekhubUserBundle:User')->findOneById($userAuth->getId());
        if (strstr($user->getEmail(),'@example.com')) {
            $user->setEmail('');
        }

        $form = $this->CreateForm(new UserForUpdateContactsType(), $user, array(
                     'user' => $user,
                     'media-manager' => $this->container->get('sonata.media.manager.media'),
                     ));

        $form->handleRequest($request);

        if ($form -> isValid()) {

            $user->setRegistrationStatus(0);
            $em->flush();
            $this->sendEmail($user);

            return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
        }

        return $this->render("GeekhubUserBundle:User:userUpdateContacts.html.twig",array('form'=>$form->createView(),'user'=>$user, 'avatar'=>$user->getAvatar()));
    }

    /**
     * @ParamConverter("mergeRequest", class="GeekhubUserBundle:MergeRequest", options={"hash" = "hash"})
     */
    public function mergeAccountsAction(MergeRequest $mergeRequest)
    {
        $proposer = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findOneById($mergeRequest->getProposersId());
        $mergingUser = $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findOneById($mergeRequest->getMergingUserId());
        $this->get('geekhub.user.accounts_merge_subscriber')->mergeUsers($proposer, $mergingUser);
        $em = $this->getDoctrine()->getManager();
        $em->remove($mergeRequest);
        $em->flush();

        return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
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

        $senderName = $this->container->getParameter('sender_name');
        $senderMail = $this->container->getParameter('sender_mail');
        $message->setFromEmail($senderMail)
            ->setFromName($senderName)
            ->addTo($user->getEmail())
            ->setSubject('REGISTRATION')
            ->setHtml($body)
        ;

        $dispatcher->send($message);
    }
}
