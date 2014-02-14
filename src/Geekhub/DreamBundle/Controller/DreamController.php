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
use Geekhub\DreamBundle\Entity\Tag;
use Geekhub\DreamBundle\Entity\Task;
use Geekhub\DreamBundle\Form\DreamType;
use Geekhub\DreamBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DreamController extends Controller
{
    public function newDreamAction(Request $request)
    {
        $dream = new Dream();

        $form = $this->createForm(new DreamType(), $dream);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $newDream = $this->getDoctrine()->getManager();

            $data = $form->getData();

//            var_dump($data); exit;
//            var_dump($data->getFinancialResources());
//            echo "****************************************";
//            var_dump($data->getEquipmentResources());
//            echo "****************************************";
//            var_dump($data->getWorkResources());
//            echo "****************************************<br>";
//            echo "*********** after del null *************<br>";
//            echo "****************************************<br>";
//            var_dump($data->getFinancialResources());
//            echo "****************************************";
//            var_dump($data->getEquipmentResources());
//            echo "****************************************";
//            var_dump($data->getWorkResources());
//            echo "****************************************";
//            exit;

            foreach ($data->getEquipmentResources() as $equip) {
                if (is_null($equip->getTitle())) {
                    $data->getEquipmentResources()->removeElement($equip);
                }

                $equip->setDream($dream);
                $dream->addDreamResource($equip);
            }
            foreach ($data->getFinancialResources() as $finance) {
                if (is_null($finance->getTitle())) {
                    $data->getFinancialResources()->removeElement($finance);
                }

                $finance->setDream($dream);
                $dream->addDreamResource($finance);
            }
            foreach ($data->getWorkResources() as $work) {
                if (is_null($work->getTitle())) {
                    $data->getWorkResources()->removeElement($work);
                }

                $work->setDream($dream);
                $dream->addDreamResource($work);
            }

            $dream->addStatus(new Status(Status::SUBMITTED));

            $tagManager = $this->get('fpn_tag.tag_manager');

            $tags = $tagManager->loadOrCreateTag($data->getTagsInput());

            $tagManager->addTag($tags, $dream);

            // after flushing the post, tell the tag manager to actually save the tags

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

    public function createTagsAction()
    {

//        $tagManager = $this->get('fpn_tag.tag_manager');
//
//        // ask the tag manager to create a Tag object
//        $fooTag = $tagManager->loadOrCreateTag('foo');
//
//        // assign the foo tag to the post
//        $tagManager->addTag($fooTag, $post);
//
//        $em = $this->getDoctrine()->getEntityManager();
//
//        // persist and flush the new post
//        $em->persist($post);
//        $em->flush();
//
//        // after flushing the post, tell the tag manager to actually save the tags
//        $tagManager->saveTagging($post);
//
//        // ...
//
//        // Load tagging ...
//        $tagManager->loadTagging($post);
    }

    public function loadTagsAction()
    {

        $tags = $this->getDoctrine()->getRepository('TagBundle:Tag')->loadTags();

        return new Response(json_encode(["tags" => $tags]));
//        return new Response(json_encode(["tags" => $tags]));
    }

}
