<?php

namespace Geekhub\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository('GeekhubResourceBundle:Faq')->findAll();

        return $this->render('GeekhubResourceBundle:Faq:index.html.twig', array(
            'faqs' => $faqs,
                'id' => $id,
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $faq = $em->getRepository('GeekhubResourceBundle:Faq')->find($id);

        if (!$faq) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        return $this->render('GeekhubResourceBundle:Faq:show.html.twig', array('faq' => $faq));
    }
}
