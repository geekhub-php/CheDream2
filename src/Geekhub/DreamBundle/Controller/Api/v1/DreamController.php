<?php

namespace Geekhub\DreamBundle\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class DreamController extends Controller
{
    const INTERVAL_IN_MONTHS = 1;

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
        $em = $this->getDoctrine()->getManager();
        $resultSetMapper = new ResultSetMappingBuilder($em);
        $resultSetMapper->addRootEntityFromClassMetadata('GeekhubDreamBundle:Dream', 'dreams');

        $limit = $paramFetcher->get('limit') ?: 5;
        $offset = $paramFetcher->get('offset') ?: 0;
        $queryString =
            'SELECT
                dreams.*,
                COUNT(fc.id)+COUNT(ec.id)+COUNT(wc.id)+COUNT(oc.id) AS contributesCount
            FROM dreams
                LEFT JOIN financial_contributes AS fc ON (dreams.id=fc.dream_id and fc.createdAt > DATE_SUB(NOW(), INTERVAL ? MONTH))
                LEFT JOIN equipment_contributes AS ec ON (dreams.id=ec.dream_id and ec.createdAt > DATE_SUB(NOW(), INTERVAL ? MONTH))
                LEFT JOIN work_contributes AS wc ON (dreams.id=wc.dream_id and wc.createdAt > DATE_SUB(NOW(), INTERVAL ? MONTH))
                LEFT JOIN other_contributes AS oc ON (dreams.id=oc.dream_id and oc.createdAt > DATE_SUB(NOW(), INTERVAL ? MONTH))
            GROUP BY dreams.id
            ';
        $userId = $paramFetcher->get('user');
        $currentStatuses = $paramFetcher->get('statuses');
        if ($userId || $currentStatuses) {
            $queryString .= 'HAVING ';
            if ($userId) {
                $queryString .= 'dreams.author_id = ?';
            }
            if ($userId && $currentStatuses) {
                $queryString .= ' AND ';
            }
            if ($currentStatuses) {
                $queryString .= 'dreams.currentStatus in ("'.implode('", "', $currentStatuses).'")';
            }
        }
        $queryString .= ' ORDER BY ? ?
            LIMIT '.$limit.'
            OFFSET '.$offset;

        $query = $em->createNativeQuery($queryString, $resultSetMapper);
        $monthsNumber = self::INTERVAL_IN_MONTHS;
        $query->setParameter(1, $monthsNumber); // months
        $query->setParameter(2, $monthsNumber); // months
        $query->setParameter(3, $monthsNumber); // months
        $query->setParameter(4, $monthsNumber); // months
        $paramsNumber = 4;
        if ($userId) {
            $query->setParameter(++$paramsNumber, $paramFetcher->get('user'));
        }
        $query->setParameter(++$paramsNumber, $paramFetcher->get('orderBy') ?: 'contributesCount');
        $query->setParameter(++$paramsNumber, $paramFetcher->get('orderDirection') ?: 'DESC');
        $dreams = $query->getResult();

        if (!$paramFetcher->get('template')) {
            return $dreams;
        }

        return $this->render("GeekhubDreamBundle:Dream:" . $paramFetcher->get('template'), array(
            'dreams'=>$dreams,
        ));
    }
}
