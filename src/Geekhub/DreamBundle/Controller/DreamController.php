<?php
/**
 * Created by PhpStorm.
 * File: DreamController.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 11:54
 */

namespace Geekhub\DreamBundle\Controller;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\EquipmentContribute;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\OtherContribute;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\DreamBundle\Entity\WorkContribute;
use Geekhub\DreamBundle\Form\DreamRejectType;
use Geekhub\DreamBundle\Form\DreamType;
use FOS\RestBundle\Controller\Annotations\View;
use Geekhub\DreamBundle\Form\EquipmentContributeType;
use Geekhub\DreamBundle\Form\FinancialContributeType;
use Geekhub\DreamBundle\Form\OtherContributeType;
use Geekhub\DreamBundle\Form\WorkContributeType;
use Geekhub\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DreamController extends Controller
{
    /**
     * @View(templateVar="form")
     */
    public function newDreamAction(Request $request)
    {
        $dream = new Dream();

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $tagManager = $this->get('geekhub.tag.tag_manager');
                $tagManager->addTagsToEntity($dream);

                $em->persist($dream);
                $em->flush();

                $tagManager->saveTagging($dream);

                return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
            }
        }

        return $form->createView();
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     * @View()
     */
    public function editDreamAction(Dream $dream, Request $request)
    {
        if (($this->getUser()->getId() != $dream->getAuthor()->getId())
            and (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) ) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        $rejectForm = $this->createForm(new DreamRejectType(), $dream);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagManager = $this->get('geekhub.tag.tag_manager');
                $tagManager->addTagsToEntity($dream);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $tagManager->saveTagging($dream);

                return $this->redirect($this->generateUrl('geekhub_dream_homepage'));
            }
        }

        return array(
            'form'          => $form->createView(),
            'slug'          =>$dream->getSlug(),
            'rejectDescription' =>$dream->getRejectedDescription(),
            'poster'        => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamFiles'    => $dream->getMediaFiles(),
            'dreamVideos'   => $dream->getMediaVideos(),
            'rejectForm'    =>$rejectForm->createView()
        );
    }

    /**
     * @View(templateVar="dreams")
     */
    public function listAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findAll();
    }

    /**
     * @View()
     */
    public function adminDreamListAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') ) {
            throw new AccessDeniedException();
        }
        return;
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     * @View(templateVar="dream, finForm, equipForm, workForm")
     */
    public function viewDreamAction(Dream $dream, Request $request)
    {
        $user = $this->getUser();
        $financialContribute = new FinancialContribute();
        $equipmentContribute = new EquipmentContribute();
        $workContribute = new WorkContribute();
        $otherContribute = new OtherContribute();
        $finForm = $this->createForm(new FinancialContributeType(), $financialContribute, array('dream' => $dream));
        $equipForm = $this->createForm(new EquipmentContributeType(), $equipmentContribute, array('dream' => $dream));
        $workForm = $this->createForm(new WorkContributeType(), $workContribute, array('dream' => $dream));
        $otherForm = $this->createForm(new OtherContributeType(), $otherContribute);

        $contributors = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->getArrayContributorsByDream($dream);

        if ($request->isMethod('POST')) {

            $this->handleContributionForms(
                $request,
                $dream,
                $user,
                $finForm,
                $equipForm,
                $workForm,
                $otherForm,
                $financialContribute,
                $equipmentContribute,
                $workContribute,
                $otherContribute
            );

            return $this->redirect($this->generateUrl('view_dream', array(
                'slug' => $dream->getSlug()
            )));
        }

        return array(
            'dream' => $dream,
            'finForm' => $finForm->createView(),
            'equipForm' => $equipForm->createView(),
            'workForm' => $workForm->createView(),
            'otherForm' => $otherForm->createView(),
            'contributors' => $contributors
        );
    }

    private function handleContributionForms(Request $request, Dream $dream, User $user, Form $finForm,
                                             Form $equipForm, Form $workForm, Form $otherForm,
                                             FinancialContribute $financialContribute,
                                             EquipmentContribute $equipmentContribute,
                                             WorkContribute $workContribute,
                                             OtherContribute $otherContribute)
    {
        if ($request->get('financialContributeForm')) {
            $finForm->handleRequest($request);
            if ($finForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $financialContribute->setDream($dream);
                $financialContribute->setUser($user);
                $em->persist($financialContribute);
                $em->flush();
            }
        }
        if ($request->get('equipmentContributeForm')) {
            $equipForm->handleRequest($request);
            if ($equipForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $equipmentContribute->setDream($dream);
                $equipmentContribute->setUser($user);
                $em->persist($equipmentContribute);
                $em->flush();
            }
        }
        if ($request->get('workContributeForm')) {
            $workForm->handleRequest($request);
            if ($workForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $workContribute->setDream($dream);
                $workContribute->setUser($user);
                $em->persist($workContribute);
                $em->flush();
            }
        }
        if ($request->get('otherContributeForm')) {
            $otherForm->handleRequest($request);
            if ($otherForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $otherContribute->setDream($dream);
                $otherContribute->setUser($user);
                $em->persist($otherContribute);
                $em->flush();
            }
        }
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function rejectDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))  {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(new DreamRejectType(), $dream);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::REJECTED));
                $em->flush();

                return $this->redirect($this->generateUrl('dream_admin_list'));
            }
        }

        return $this->redirect($this->generateUrl('dream_admin_list'));
    }

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function confirmDreamAction(Dream $dream, Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))  {
            throw new AccessDeniedException();
        }
        if ($request->isMethod('POST')) {
                $em = $this->getDoctrine()->getManager();
                $dream->addStatus(new Status(Status::COLLECTING_RESOURCES));
                $dream->setRejectedDescription(null);
                $em->flush();

                return $this->redirect($this->generateUrl('dream_admin_list'));
        }

        return $this->redirect($this->generateUrl('dream_admin_list'));
    }

}
