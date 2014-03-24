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
use Geekhub\DreamBundle\Form\DreamType;
use FOS\RestBundle\Controller\Annotations\View;
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
     * @View(templateVar="dream")
     */
    public function viewDreamAction(Dream $dream)
    {
        return $dream;
    }

    /**
     * @View(templateVar="dreams")
     */
    public function viewAllDreamsAction($status, $offset)
    {
        $dreamRepository = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream');
        if ($status =="new") {
            return $dreamRepository->findNewDreams(8, $offset);
        }
        else if ($status =="popular") {
            return $dreamRepository->findPopularDreams(8, $offset);
        }
        else { 
            return $dreamRepository->findLimitedDreamsByStatus($status, 8, $offset);
        }
    }
}
