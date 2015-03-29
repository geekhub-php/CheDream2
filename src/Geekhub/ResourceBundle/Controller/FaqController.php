<?php

namespace Geekhub\ResourceBundle\Controller;

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
}
