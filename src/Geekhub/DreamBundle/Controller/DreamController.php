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

class DreamController extends Controller
{
    public function newDreamAction(Request $request)
    {
        $dream = new Dream();

        $form = $this->createForm(new DreamType(), $dream, array(
            'dream' => $dream
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $newDream = $this->getDoctrine()->getManager();

            $dream->addStatus(new Status(Status::SUBMITTED));

            $tagManager = $this->get('fpn_tag.tag_manager');
            $tagsObjArray = $tagManager->loadOrCreateTags($dream->getTags());
            $dream->setTags(null);
            $tagManager->addTags($tagsObjArray, $dream);

            $newDream->persist($dream);
            $newDream->flush();

            $tagManager->saveTagging($dream);

            return $this->redirect($this->generateUrl('dream_list'));
        }

        return $this->render('GeekhubDreamBundle:Dream:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listAllDreamAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

        $tagManager = $this->get('fpn_tag.tag_manager');
        foreach ($dreams as $dream) {
            $tagManager->loadTagging($dream);
        }

        return  $this->render('GeekhubDreamBundle:Dream:list.html.twig', array(
            'dreams' => $dreams
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
