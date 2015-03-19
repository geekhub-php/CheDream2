<?php

namespace Geekhub\DreamBundle\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\FOSRestController;

class DreamController extends FOSRestController
{
    /**
     * <strong>Simple example:</strong><br />
     * http://chedream.local/api/v1/dreams.json?limit=15&statuses[]=implementing&statuses[]=collecting-resources
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         500="Returned when something went wrong :(",
     *     },
     *  description="This is a description of your API method",
     *  section="dreams",
     *  output="Geekhub\DreamBundle\Model\Dreams"
     * )
     *
     * @QueryParam(name="limit", requirements="\d+", default="8", description="Count dreams")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="From what offset")
     * @QueryParam(name="user", description="Find all dream by user with given username")
     * @QueryParam(name="orderBy", description="Order field name")
     * @QueryParam(name="orderDirection", default="DESC", description="Order direction: asc or desc")
     * @QueryParam(array=true, name="statuses", description="Array of dreams statuses")
     * @QueryParam(name="template", default=false, description="From what offset")
     *
     * @View(templateVar="dreams")
     */
    public function getDreamsAction(ParamFetcher $paramFetcher)
    {
        $criteria = array(
            'user' => $paramFetcher->get('user'),
            'currentStatus' => $paramFetcher->get('statuses'),
        );

        $orderArray = $paramFetcher->get('orderBy') ? [$paramFetcher->get('orderBy') => $paramFetcher->get('orderDirection')] : array();

        $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findBy(
            array_filter($criteria),
            $orderArray,
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        if (!$paramFetcher->get('template')) {
            return $dreams;
        }

        return $this->render("GeekhubDreamBundle:Dream:" . $paramFetcher->get('template'), array(
            'dreams'=>$dreams,
        ));
    }

    /**
     * Get single Dream for slug,.
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets Dream for slug",
     * output="Geekhub\DreamBundle\Model\Dreams",
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
        $dreams = $this->getDoctrine()->getManager()
            ->getRepository('GeekhubDreamBundle:Dream')
            ->findOneBySlug($slug);

        if (!$dreams) {
            throw new NotFoundHttpException();
        }

        return $dreams;
    }

}
