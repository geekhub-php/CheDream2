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

class DreamController extends Controller
{
    public function newDreamAction()
    {
        $dream = new Dream();
        $form = $this->createForm(new DreamType(), $dream);

        return $this->render('GeekhubDreamBundle:Dream:newDream.html.twig', array(
            'form' => $form->createView()
        ));
    }
} 