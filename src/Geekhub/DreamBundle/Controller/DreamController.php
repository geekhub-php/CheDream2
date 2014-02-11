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

        $fin = new DreamResource();
        $fin->setTitle('fdfdfsd');
        $fin->setQuantity(43442);
        $dream->getFinancialResources()->add($fin);

        $fin2 = new DreamResource();
        $fin2->setTitle('fin2 t');
        $fin2->setQuantity(333333);
        $dream->getFinancialResources()->add($fin2);

        $eq = new DreamResource();
        $eq->setTitle('eq 1');
        $eq->setQuantity(123);
        $eq->setQuantityType(AbstractContributeResource::KG);
        $dream->getEquipmentResources()->add($eq);

        $w = new DreamResource();
        $w->setTitle('work1');
        $w->setQuantity(777);
        $w->setQuantityDays(7);
        $dream->getWorkResources()->add($w);

        $form = $this->createForm(new DreamType(), $dream);

        $form->handleRequest($request);

        if ($form->isValid()) {

        }

        return $this->render('GeekhubDreamBundle:Dream:newDream.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
