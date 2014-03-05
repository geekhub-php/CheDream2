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
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\DreamBundle\Form\DreamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DreamController extends Controller
{
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

        return $this->render('GeekhubDreamBundle:Dream:newDream.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editDreamAction($slug, Request $request)
    {
        $user = $this->getUser();
        $mediaManager = $this->get('sonata.media.manager.media');
        $tagManager = $this->get('fpn_tag.tag_manager');
        $em = $this->getDoctrine()->getManager();
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneBySlug($slug);
        $tagManager->loadTagging($dream);

        if ($user->getId() != $dream->getAuthor()->getId()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $mediaManager
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $tagsObjArray = $tagManager->loadOrCreateTags($dream->getTags());
                $dream->setTags(null);
                $tagManager->addTags($tagsObjArray, $dream);

                $em->flush();
                $tagManager->saveTagging($dream);

                return $this->redirect($this->generateUrl('dream_list'));
            }
        }

        /** @var \GeekHub\DreamBundle\Entity\Dream $dream */

        return $this->render('GeekhubDreamBundle:Dream:editDream.html.twig', array(
            'form' => $form->createView(),
            'poster' => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamFiles' => $dream->getMediaFiles(),
            'dreamVideos'   => $dream->getMediaVideos()
        ));

    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

        $tagManager = $this->get('fpn_tag.tag_manager');
        foreach ($dreams as $dream) {
            $tagManager->loadTagging($dream);
        }

        return  $this->render('GeekhubDreamBundle:Dream:list.html.twig', array(
            'dreams' => $dreams,
        ));
    }
}
