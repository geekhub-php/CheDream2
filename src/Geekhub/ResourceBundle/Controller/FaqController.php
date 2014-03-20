<?php

namespace Geekhub\ResourceBundle\Controller;

use Geekhub\ResourceBundle\Entity\Faq;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    /**
     * @Template
     */
    public function faqListAction($slug)
    {
        $faqs = $this->getFaqsAction();
        if (is_null($slug)) {
            $slug = $faqs[0]->getSlug();
        }

        return array(
            'faqs' => $faqs,
            'slug' => $slug,
        );
    }

    /**
     * @return array
     * @View(templateVar="faqs")
     */
    public function getFaqsAction()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('GeekhubResourceBundle:Faq')
            ->findAll();
    }
}
