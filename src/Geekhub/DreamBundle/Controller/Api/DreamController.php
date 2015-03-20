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

class DreamController extends FOSRestController
{
    /**
     * Get single Dream,
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets all Dream",
     * output = "AppBundle\Document\Dream",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the Dream is not found"
     * }
     * )
     *
     *
     * RestView()
     *
     * @QueryParam(name="count", requirements="\d+", default="10", description="Count statuses at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     *
     * @param  ParamFetcher $paramFetcher
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getDreamsAction(ParamFetcher $paramFetcher)
    {
        $manager = $this->getDoctrine()->getManager();

        $dreams = $manager->getRepository('GeekhubDreamBundle:Dream')->findAll();

        $selfPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page'),
        ));

        $nextPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page')+1,
        ));

        $prevPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page')-1,
        ));

        $firstPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page'),
        ));
//
//        $lastPage = $this->generateUrl('api_v1_get_dreams', array(
//            'count' => $paramFetcher->get('count'),
//            'page' => $paramFetcher->get('page')-1,
//        ));

        $dreamsResponse = new DreamsResponse();

        $dreamsResponse->setDreams($dreams);
        $dreamsResponse->setSelfPage($selfPage);
        $dreamsResponse->setNextPage($nextPage);
        $dreamsResponse->setPrevPage($prevPage);
        $dreamsResponse->setFirstPage($firstPage);

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
     * }
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
