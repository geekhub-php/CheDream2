<?php

namespace Geekhub\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Geekhub\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\UserBundle\Form\UserType;
use Geekhub\UserBundle\Form\UserForUpdateContactsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Form\FormError;

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


        if ($form->isValid()) {
            $mergedUser = $em->getRepository('GeekhubUserBundle:User')
                ->findOneBy(array(
                    'email' => trim($form->get('email')->getData())
                ))
            ;

            if ($mergedUser == null || $mergedUser->getId() == $user->getId()) {
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'dreamMessage',
                    'Ваш профіль відредаговано.'
                );
                return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
            } 
            $form->get('email')->addError(new FormError('Така адреса вже використовується.'));
        }

        return $this->render("GeekhubUserBundle:User:user.html.twig",array('form'=>$form->createView(),'user'=>$user, 'avatar'=>$user->getAvatar()));
    }

    /**
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     * @View()
     */
    public function viewAction(User $user)
    {
        $contributions = $this->getContributions($user);
        $contributedDreams = $this->getContributionsDream($contributions);

        return array(
                'user' => $user,
                'contributedDreams' => $contributedDreams,
            );
    }

    /**
     * @param  User $user
     *
     * @return ArrayCollection
     */
    protected function getContributions(User $user)
    {
        $contributions = new ArrayCollection();
        if (!$user->getFinancialContributions()->isEmpty()) {
            foreach ($user->getFinancialContributions() as $finance) {
                $contributions->add($finance);
            }
        }

        if (!$user->getEquipmentContributions()->isEmpty()) {
            foreach ($user->getEquipmentContributions() as $equipment) {
                $contributions->add($equipment);
            }
        }

        if (!$user->getWorkContributions()->isEmpty()) {
            foreach ($user->getWorkContributions() as $work) {
                $contributions->add($work);
            }
        }

        if (!$user->getOtherContributions()->isEmpty()) {
            foreach ($user->getOtherContributions() as $work) {
                $contributions->add($work);
            }
        }

        return $contributions;
    }

    /**
     * @param  ArrayCollection $contributions
     *
     * @return ArrayCollection
     */
    protected function getContributionsDream(ArrayCollection $contributions)
    {
        $contributedDreams = new ArrayCollection();
        if (!$contributions->isEmpty()) {
            foreach ($contributions as $contribution) {
                if (!$contributedDreams->contains($contribution->getDream())) {
                    $contributedDreams->add($contribution->getDream());
                }
            }
        }

        return $contributedDreams;
    }

    /**
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     * @View(TemplateVar="dreams")
     */
    public function userOwnedDreamsViewAction($user, $status = "any")
    {
        switch ($status) {
            case "any":
                if ($this->getUser()==$user) {
                    return $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findBy(array('author' => $user));
                } else {
                    return $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findUserApprovedDreams($user);
                }
                break;
            case "projects":
                if ($this->getUser()==$user) {
                    return $this->getDoctrine()->getRepository('GeekhubUserBundle:User')->findMyDreamProjects($user);
                } else {
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

        if (strstr($user->getEmail(),'@example.com')) {
            $user->setEmail('');
        }

        $form = $this->CreateForm(new UserForUpdateContactsType(), $user, array(
                     'user' => $user,
                     'media-manager' => $this->container->get('sonata.media.manager.media'),
                     ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mergedUser = $em->getRepository('GeekhubUserBundle:User')
                ->findOneBy(array(
                    'email' => trim($form->get('email')->getData())
                ))
            ;

            if ($mergedUser == null) {
                $em->flush();

                return $this->redirect($this->generateUrl("geekhub_dream_homepage"));
            } else {
                return $this->render('GeekhubUserBundle:User:mergeAccounts.html.twig', array(
                    'mergedUser' => $mergedUser,
                    'currentUser' => $user,
                ));
            }
        }

        return array(
            'form'=>$form->createView(),
            'user'=>$user,
            'avatar'=>$user->getAvatar()
        );
    }

    /**
     * @param  Request $request
     * @ParamConverter("user", class="GeekhubUserBundle:User")
     * @param  User    $user
     * @return mixed
     */
    public function mergeAccountsAction(Request $request, User $user)
    {
        $socialNetworks = $user->getNotNullSocialIds();
        $resourceOwner = $this->container->get(sprintf("hwi_oauth.resource_owner.%s", key($socialNetworks)));

        return $this->container->get('hwi_oauth.connect_controller')->redirectToServiceAction($request, key($socialNetworks));
    }
}
