<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository('GeekhubDreamBundle:Faq')->findAll();

        return $this->render('GeekhubDreamBundle:Faq:index.html.twig', array(
            'faqs' => $faqs,
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $faq = $em->getRepository('GeekhubDreamBundle:Faq')->find($id);

        if (!$faq) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        return $this->render('GeekhubDreamBundle:Faq:show.html.twig', array('faq' => $faq));
    }
}
