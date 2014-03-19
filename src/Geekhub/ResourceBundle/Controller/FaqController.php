<?php

namespace Geekhub\ResourceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    /**
     * @Template
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository('GeekhubResourceBundle:Faq')->findAll();
//        var_dump($faqs[0]->getSlug()); exit;
        if (is_null($slug)) {
            $slug = $faqs[0]->getSlug();
        }

        return array(
            'faqs' => $faqs,
            'slug' => $slug,
        );
    }

    /**
     * @Template
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $faq = $em->getRepository('GeekhubResourceBundle:Faq')->findOneBy(array('slug' => $slug));;

        if (!$faq) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        return array('faq' => $faq);
    }
}
