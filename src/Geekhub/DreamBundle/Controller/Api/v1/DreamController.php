<?php

namespace Geekhub\DreamBundle\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class DreamController extends Controller
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
     * @QueryParam(array=true, name="orderBy", description="Array of order field")
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

        $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findBy(
            array_filter($criteria),
            $paramFetcher->get('orderBy'),
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
}
