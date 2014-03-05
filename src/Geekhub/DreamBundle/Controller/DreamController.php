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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @ParamConverter("dream", class="GeekhubDreamBundle:Dream")
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

        return $this->render('GeekhubDreamBundle:Dream:editDream.html.twig', array(
            'form'          => $form->createView(),
            'poster'        => $dream->getMediaPoster(),
            'dreamPictures' => $dream->getMediaPictures(),
            'dreamFiles'    => $dream->getMediaFiles(),
            'dreamVideos'   => $dream->getMediaVideos()
        ));

    }

    public function listAction()
    {
        $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findAll();

        return  $this->render('GeekhubDreamBundle:Dream:list.html.twig', array(
            'dreams' => $dreams,
        ));
    }

    public function viewAction($slug)
    {
        /** @var \GeekHub\DreamBundle\Entity\Dream $dream */
        $em = $this->getDoctrine()->getManager();
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneBySlug($slug);

        $dreamPosterMedia = $dream->getMediaPoster();

        $mediaPool = $this->get('sonata.media.pool');

        $provider = $mediaPool->getProvider($dreamPosterMedia->getProviderName());

        $format = $provider->getFormatName($dreamPosterMedia, 'reference');
        $posterSrc = $provider->generatePublicUrl($dreamPosterMedia, $format);
        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($dream);

        return  $this->render('GeekhubDreamBundle:Dream:showDream.html.twig', array(
            'dream' => $dream,
            'poster'    => $posterSrc
        ));

    }
}
