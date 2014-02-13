<?php
/**
 * Created by PhpStorm.
 * File: DreamController.php
 * User: Yuriy Tarnavskiy
 * Date: 11.02.14
 * Time: 11:54
 */

namespace Geekhub\DreamBundle\Controller;

use Geekhub\DreamBundle\Entity\AbstractContributeResource;
use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\DreamResource;
use Geekhub\DreamBundle\Entity\Status;
use Geekhub\DreamBundle\Entity\Tag;
use Geekhub\DreamBundle\Entity\Task;
use Geekhub\DreamBundle\Form\DreamType;
use Geekhub\DreamBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

//            $status = new Status();
//            $status->setStatus(Status::SUBMITTED);
//            $status->setDream($dream);
//            $dream->setStatus($status);

            $newDream->persist($dream);
            $newDream->flush();

            return $this->redirect($this->generateUrl('dream_list'));
        }

        return $this->render('GeekhubDreamBundle:Dream:newDream.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listAllDreamAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dreams = $em->getRepository('GeekhubDreamBundle:Dream')->findAll();

        return  $this->render('GeekhubDreamBundle:Dream:listAllDream.html.twig', array(
            'dreams' => $dreams
        ));
    }

}
