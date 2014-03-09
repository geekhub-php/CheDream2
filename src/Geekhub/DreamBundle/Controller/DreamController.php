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
use Geekhub\DreamBundle\Entity\WorkContribute;
use Geekhub\DreamBundle\Form\DreamType;
use FOS\RestBundle\Controller\Annotations\View;
use Geekhub\DreamBundle\Form\EquipmentContributeType;
use Geekhub\DreamBundle\Form\FinancialContributeType;
use Geekhub\DreamBundle\Form\WorkContributeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

                return $this->redirect($this->generateUrl('dream_list'));
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
        if ($this->getUser()->getId() != $dream->getAuthor()->getId()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $this->get('sonata.media.manager.media')
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagManager = $this->get('geekhub.tag.tag_manager');
                $tagManager->addTagsToEntity($dream);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $tagManager->saveTagging($dream);

                return $this->redirect($this->generateUrl('dream_list'));
            }
        }

        return array(
            'form'          => $form->createView(),
            'poster'        => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamFiles'    => $dream->getMediaFiles(),
            'dreamVideos'   => $dream->getMediaVideos()
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
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
     */
    public function viewDreamAction(Dream $dream, Request $request)
    {
        $user = $this->getUser();
        $financialContribute = new FinancialContribute();
        $equipmentContribute = new EquipmentContribute();
        $workContribute = new WorkContribute();
        $finForm = $this->createForm(new FinancialContributeType(), $financialContribute);
        $equipForm = $this->createForm(new EquipmentContributeType(), $equipmentContribute);
        $workForm = $this->createForm(new WorkContributeType(), $workContribute);

        if ($request->isMethod('POST')) {

            if($request->get('financialContributeForm')) {
                $finForm->handleRequest($request);
                if ($finForm->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $financialContribute->setDream($dream);
                    $financialContribute->setUser($user);
                    $em->persist($financialContribute);
                    $em->flush();

                    return $this->redirect($this->generateUrl('view_dream', array(
                        'slug' => $dream->getSlug()
                    )));
                }
            }
            if($request->get('equipmentContributeForm')) {
                $equipForm->handleRequest($request);
                if ($equipForm->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $equipmentContribute->setDream($dream);
                    $equipmentContribute->setUser($user);
                    $em->persist($equipmentContribute);
                    $em->flush();

                    return $this->redirect($this->generateUrl('view_dream', array(
                        'slug' => $dream->getSlug()
                    )));
                }
            }
            if($request->get('workContributeForm')) {
                $workForm->handleRequest($request);
                if ($workForm->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $workContribute->setDream($dream);
                    $workContribute->setUser($user);
                    $em->persist($workContribute);
                    $em->flush();

                    return $this->redirect($this->generateUrl('view_dream', array(
                        'slug' => $dream->getSlug()
                    )));
                }
            }
        }

        return $this->render('GeekhubDreamBundle:Dream:viewDream.html.twig', array(
            'dream' => $dream,
            'finForm'=>$finForm->createView(),
            'equipForm'=>$equipForm->createView(),
            'workForm'=>$workForm->createView(),
        ));
    }
}
