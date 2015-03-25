<?php

namespace Geekhub\DreamBundle\Controller\Api;

use Geekhub\DreamBundle\Model\DreamsResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\FOSRestController;
use Geekhub\DreamBundle\Service\PaginatorService;

class DreamController extends FOSRestController
{
    /**
     * Get dreams for parameter,<br />
     *      * <strong>Simple example:</strong><br />
     * http://chedream2/app_dev.php/api/dreams.json?count=2&page=2&sort_by=id&sort_order=ASC
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets all Dream",
     * output = "Geekhub\DreamBundle\Entity\Dream",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the Dream is not found"
     * },
     * section="All dreams "
     * )
     *
     *
     * RestView()
     *
     * @QueryParam(name="count", requirements="\d+", default="10", description="Count dreams at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="sort_by", strict=true, requirements="^[a-zA-Z]+", default="createdAt", description="Sort by", nullable=true)
     * @QueryParam(name="sort_order", strict=true, requirements="^[a-zA-Z]+", default="DESC", description="Sort order", nullable=true)
     *
     * @param  ParamFetcher $paramFetcher
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getDreamsAction(ParamFetcher $paramFetcher)
    {
        $manager = $this->getDoctrine()->getManager();

        $dreams = $manager->getRepository('GeekhubDreamBundle:Dream')->
        findBy(
            [],
            [$paramFetcher->get('sort_by') => $paramFetcher->get('sort_order')],
            $paramFetcher->get('count'),
            $paramFetcher->get('count') * ($paramFetcher->get('page') - 1)
        );

        $dreamsAll = $manager->getRepository('GeekhubDreamBundle:Dream')->findAll();

        $paginator = $this->get('paginator');

        $pagination = $paginator->getPaginated(
            $paramFetcher->get('count'),
            $paramFetcher->get('page'),
            $paramFetcher->get('sort_by'),
            $paramFetcher->get('sort_order'),
            $dreamsAll
        );

        $dreamsResponse = new DreamsResponse();

        $dreamsResponse->setDreams($dreams);
        $dreamsResponse->setSelfPage($pagination->getSelfPage());
        $dreamsResponse->setNextPage($pagination->getNextPage());
        $dreamsResponse->setPrevPage($pagination->getPrevPage());
        $dreamsResponse->setFirstPage($pagination->getFirstPage());
        $dreamsResponse->setLastPage($pagination->getLastPage());

        return $dreamsResponse;
    }
    /**
     * Get single Dream for slug,.
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets Dream for slug",
     * output="Geekhub\DreamBundle\Entity\Dream",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the Dream is not found"
     * },
     * section="Dream for slug"
     * )
     *
     * @RestView()
     * @param
     *
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getDreamAction($slug)
    {
        $dream = $this->getDoctrine()->getManager()
            ->getRepository('GeekhubDreamBundle:Dream')
            ->findOneBySlug($slug);
        if (!$dream) {
            throw new NotFoundHttpException();
        }

        return $dream;
    }
}