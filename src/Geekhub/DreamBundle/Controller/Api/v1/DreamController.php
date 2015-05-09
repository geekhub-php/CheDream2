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

        $query = $em->createQueryBuilder()
            ->select('dreams.*')
            ->addSelect('COUNT(fc.id)+COUNT(ec.id)+COUNT(wc.id)+COUNT(oc.id) as contributesCount')
            ->from('dreams', 'dreams')
            ->leftJoin('financial_contributes', 'fc', 'on', 'dreams.id=fc.dream_id and fc.createdAt > DATE_SUB(NOW(), INTERVAL ' . self::INTERVAL_IN_MONTHS . ' MONTH)')
            ->leftJoin('equipment_contributes', 'ec', 'on', 'dreams.id=ec.dream_id and ec.createdAt > DATE_SUB(NOW(), INTERVAL ' . self::INTERVAL_IN_MONTHS . ' MONTH)')
            ->leftJoin('work_contributes', 'wc', 'on', 'dreams.id=wc.dream_id and wc.createdAt > DATE_SUB(NOW(), INTERVAL ' . self::INTERVAL_IN_MONTHS . ' MONTH)')
            ->leftJoin('other_contributes', 'oc', 'on', 'dreams.id=oc.dream_id and oc.createdAt > DATE_SUB(NOW(), INTERVAL ' . self::INTERVAL_IN_MONTHS . ' MONTH)')
            ->groupBy('dreams.id')
            ->orderBy($paramFetcher->get('orderBy') ?: 'contributesCount', $paramFetcher->get('orderDirection') ?: 'DESC')
        ;

        if ($userId = $paramFetcher->get('user')) {
            $query->andWhere('dreams.author_id = :user_id');
            $query->setParameter(':user_id', $userId);
        }
        if ($currentStatuses = $paramFetcher->get('statuses')) {
            $query->andWhere('dreams.currentStatus in ("'.implode('", "', $currentStatuses).'")');
        }
        $queryString = $query->getDql();
        $queryString .= ' LIMIT '.($paramFetcher->get('limit') ?: 8).' OFFSET '.($paramFetcher->get('offset') ?: 0);

        $query = $em->createNativeQuery($queryString, $resultSetMapper);
        $dreams = $query->getResult();

        if (!$paramFetcher->get('template')) {
            return $dreams;
        }

        return $this->render("GeekhubDreamBundle:Dream:" . $paramFetcher->get('template'), array(
            'dreams'=>$dreams,
        ));
    }
}
