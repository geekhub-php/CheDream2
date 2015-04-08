<?php

namespace Geekhub\ResourceBundle\Controller\Api;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\FOSRestController;

class FaqController extends FOSRestController
{
    /**
     * Get Faqs for parameter,<br />
     *      * <strong>Simple example:</strong><br />
     * http://api.chedream.local/app_dev.php/faqs.json?count=2&page=2
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets all Faq",
     * output="array<Geekhub\ResourceBundle\Entity\Faq>",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      204 = "Returned when the Faqs is not found"
     * },
     * section="All faqs "
     * )
     *
     * @QueryParam(name="count", requirements="\d+", default="10", description="Count faqs at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     *
     * RestView()
     *
     * @param  ParamFetcher $paramFetcher
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getFaqsAction(ParamFetcher $paramFetcher)
    {
        $manager = $this->getDoctrine()->getManager();

        $faqsQuery = $manager->getRepository('GeekhubResourceBundle:Faq')->findBy(
            [],
            [],
            $paramFetcher->get('count'),
            $paramFetcher->get('count') * ($paramFetcher->get('page') - 1)
        );

        return $faqsQuery;
    }

    /**
     * Get single Faq for slug,.
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets Dream for Faq",
     * output="Geekhub\ResourceBundle\Entity\Faq",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the faq is not found"
     * },
     * section="Faq for slug"
     * )
     *
     * RestView()
     * @param
     *
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getFaqAction($slug)
    {
        $faq = $this->getDoctrine()->getManager()
            ->getRepository('GeekhubResourceBundle:Faq')
            ->findOneBySlug($slug);
        if (!$faq) {
            throw new NotFoundHttpException();
        }

        return $faq;
    }
}
