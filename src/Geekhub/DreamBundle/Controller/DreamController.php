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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;

class DreamController extends Controller
{
    public function newDreamAction(Request $request)
    {
        $dream = new Dream();
        $mediaManager = $this->get('sonata.media.manager.media');
        $user = $this->getUser();

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream,
            'media-manager' => $mediaManager
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $dream->addStatus(new Status(Status::SUBMITTED));

            $tagManager = $this->get('fpn_tag.tag_manager');
            $tagsObjArray = $tagManager->loadOrCreateTags($dream->getTags());
            $dream->setTags(null);
            $tagManager->addTags($tagsObjArray, $dream);

            if (!is_null($user)) {
                $dream->setAuthor($user);
            }

            $em->persist($dream);
            $em->flush();

            $tagManager->saveTagging($dream);

//            $filesm = $dream->getMedia();

//            var_dump($filesm->first()->getBinaryContent()->getRealPath()); exit;
//            var_dump($filesm->first()->getBinaryContent()); exit;
            return $this->redirect($this->generateUrl('dream_list'));
        }

        return $this->render('GeekhubDreamBundle:Dream:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function MediaInfoAction($id)
    {
        $mediaManager = $this->get('sonata.media.manager.media');
        $media = $mediaManager->findOneBy(array('id' => $id));

        return $this->render('GeekhubDreamBundle:Dream:mediaInfo.html.twig', array(
            'media' => $media
        ));
//        echo $media->getMetadataValue('title', 'if none use this string');
    }

    public function listAllDreamAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

//        $mediaManager = $this->get('sonata.media.manager.media');
//        $media = $mediaManager->findBy(array('context' => 'pictures'));

        $tagManager = $this->get('fpn_tag.tag_manager');
        foreach ($dreams as $dream) {
            $tagManager->loadTagging($dream);
        }

        return  $this->render('GeekhubDreamBundle:Dream:list.html.twig', array(
            'dreams' => $dreams,
//            'mmm'   => $media
        ));
    }

    public function changeStatusAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneById(1);

        $dream->addStatus(new Status(Status::COMPLETED));

        $em->flush();

        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

        return  $this->render('GeekhubDreamBundle:Dream:list.html.twig', array(
            'dreams' => $dreams
        ));
    }
}
