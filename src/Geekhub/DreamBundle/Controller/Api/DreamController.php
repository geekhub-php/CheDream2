<?php

namespace Geekhub\DreamBundle\Controller\Api;

use Geekhub\DreamBundle\Model\DreamsResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\FOSRestController;

class DreamController extends FOSRestController
{
//    public function getNbPages($dreamsAll, $count)
//    {
//        $nbPages = $this->calculateNbPages($dreamsAll, $count);
//        if ($nbPages == 0) {
//            return $this->minimumNbPages();
//        }
//        return $nbPages;
//    }
//
//    private function calculateNbPages($dreamsAll, $count)
//    {
//        return (int) ceil($dreamsAll / $count);
//    }
//
//    private function minimumNbPages()
//    {
//        return 1;
//    }

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
        $dreams = $manager->getRepository('GeekhubDreamBundle:Dream')->findBy([],[$paramFetcher->get('sort_by') => $paramFetcher->get('sort_order')], $paramFetcher->get('count'), $paramFetcher->get('page'));
        $dreamsAll = $manager->getRepository('GeekhubDreamBundle:Dream')->findAll();
        $selfPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page'),
            'sort_by' => $paramFetcher->get('sort_by'),
            'sort_order' => $paramFetcher->get('sort_order'),
        ));
        $nextPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page')+1,
        ));
        if ($paramFetcher->get('page') == 1) {
            $prevPage = 0;
        } else {
            $prevPage = $this->generateUrl('get_dreams', array(
                'count' => $paramFetcher->get('count'),
                'page' => $paramFetcher->get('page') - 1,
            ));
        }
        $firstPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => 1,
        ));
        $lastPage = $this->generateUrl('get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => (int) ceil(count($dreamsAll) / $paramFetcher->get('count')),
        ));
        $dreamsResponse = new DreamsResponse();
        $dreamsResponse->setDreams($dreams);
        $dreamsResponse->setSelfPage($selfPage);
        $dreamsResponse->setNextPage($nextPage);
        $dreamsResponse->setPrevPage($prevPage);
        $dreamsResponse->setFirstPage($firstPage);
        $dreamsResponse->setLastPage($lastPage);

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
