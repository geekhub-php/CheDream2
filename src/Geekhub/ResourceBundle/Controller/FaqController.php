<?php

namespace Geekhub\ResourceBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Geekhub\DreamBundle\Entity\Status;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *  description="Returned a collection of FAQs.",
     *  statusCodes={
     *      200="Returned when file was correct load to the server",
     *      401="Returned when user not authorized",
     *      403="Returned if file type or file size not allowed",
     *      500="Returned if something went wrong",
     *  },
     *  section="FAQ-API"
     * )
     *
     * @return array
     * @View(templateVar="faqs")
     */
    public function getFaqsAction()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('GeekhubResourceBundle:Faq')
            ->findAll();
    }

    /**
     * @Route("/email/registration", name="email_registration")
     * @Template("GeekhubResourceBundle:Email:registration.html.twig")
     */
    public function emailRegistrationAction()
    {
        return ['user' => $this->getUser()];
    }

    /**
     * @Route("/email/collecting", name="email_collecting")
     * @Template("GeekhubResourceBundle:Email:collecting-resources.html.twig")
     */
    public function emailCollectingAction()
    {
        return ['dream' => $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findOneBy(['currentStatus' => Status::COLLECTING_RESOURCES])];
    }

    /**
     * @Route("/email/contribution", name="email_contribution")
     * @Template("GeekhubResourceBundle:Email:contribution.html.twig")
     */
    public function emailContributionAction()
    {
        return [
            'dream' => $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findOneBy(['id' => 1]),
            'contributor' => $this->getDoctrine()->getRepository('GeekhubDreamBundle:FinancialContribute')->findOneBy(['user' => $this->getUser()])
        ];
    }
}
