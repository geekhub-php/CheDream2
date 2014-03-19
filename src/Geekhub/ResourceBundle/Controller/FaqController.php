<?php

namespace Geekhub\ResourceBundle\Controller;

use Geekhub\ResourceBundle\Entity\Faq;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    /**
     * @Template
     */
    public function indexAction()
    {
        $faqs = $this->getDoctrine()->getManager()->getRepository('GeekhubResourceBundle:Faq')
            ->findAll();

        return array('faqs' => $faqs);
    }
//
//    /**
//     * @ParamConverter("faq", class="GeekhubResourceBundle:Faq")
//     * @Template
//     */
//    public function showAction(Faq $faq)
//    {
//        return array('faq' => $faq);
//    }
}
