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
use Symfony\Component\HttpFoundation\Request;

class DreamController extends FOSRestController
{
    /**
     * Get dreams for parameter,<br />
     *      * <strong>Simple example:</strong><br />
     * http://api.chedream.local/app_dev.php/dreams.json?status=submitted&count=2&page=2&sort_by=id&sort_order=ASC
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
     * @QueryParam(name="status", strict=true, requirements="[a-z]+", description="Status", nullable=true)
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

        $repository = $manager->getRepository('GeekhubDreamBundle:Dream');

        if(!$paramFetcher->get('status')){
            $queryBuilder = $repository->createQueryBuilder('dream')
                ->where('dream.currentStatus != :identifier1','dream.currentStatus != :identifier2')
                ->setParameter('identifier1', 'fail')
                ->setParameter('identifier2', 'rejected')
                ->orderBy('dream.'.$paramFetcher->get('sort_by'), $paramFetcher->get('sort_order'))
                ->setFirstResult($paramFetcher->get('count') * ($paramFetcher->get('page') - 1))
                ->setMaxResults($paramFetcher->get('count'))
                ->getQuery()
                ->getResult()
            ;
        } else {
            $queryBuilder = $repository->createQueryBuilder('dream')
                ->where('dream.currentStatus = :identifier')
                ->setParameter('identifier', $paramFetcher->get('status'))
                ->orderBy('dream.'.$paramFetcher->get('sort_by'), $paramFetcher->get('sort_order'))
                ->setFirstResult($paramFetcher->get('count') * ($paramFetcher->get('page') - 1))
                ->setMaxResults($paramFetcher->get('count'))
                ->getQuery()
                ->getResult()
            ;
        }

        $dreamsAll = $repository->findAll();

        $paginator = $this->get('paginator');

        $pagination = $paginator->getPaginated(
            $paramFetcher->get('count'),
            $paramFetcher->get('page'),
            $paramFetcher->get('sort_by'),
            $paramFetcher->get('sort_order'),
            $dreamsAll
        );

        $dreamsResponse = new DreamsResponse();

        $dreamsResponse->setDreams($queryBuilder);
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

    /**
     * Create dream
     *
     * @ApiDoc(
     *      resource = true,
     *      description = "Create single dream",
     *      parameters={
     *          {"name"="title", "dataType"="string", "required"=true, "description"="Dream name"},
     *          {"name"="description", "dataType"="string", "required"=true, "description"="Description about dream"},
     *          {"name"="phone", "dataType"="integer", "required"=true, "description"="Phone number", "format"="(xxx) xxx xxx xxx"},
     *          {"name"="dreamEquipmentResources", "dataType"="array<Geekhub\DreamBundle\Entity\EquipmentResource>", "required"=true, "description"="Equipment resources"},
     *          {"name"="dreamWorkResources", "dataType"="array<Geekhub\DreamBundle\Entity\WorkResource>", "required"=true, "description"="Work resources"},
     *          {"name"="dreamFinancialResources", "dataType"="array<Geekhub\DreamBundle\Entity\FinancialResource>", "required"=true, "description"="Financial resources"}
     *      },
     *      output = "string",
     *      statusCodes = {
     *          201 = "Dream sucessful created",
     *          400 = "When dream not created"
     *      },
     * section="Post Dream"
     * )
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postDreamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $user = $this->getUser();
        $data = $this->get('serializer')->serialize($data, 'json');
        $dream = $this->get('serializer')->deserialize($data, 'Geekhub\DreamBundle\Entity\Dream', 'json');
        $dream->setAuthor($user);
        $em->persist($dream);
        $em->flush();
        $restView = View::create();
        $restView->setStatusCode(201);
        $restView->setData([
            "link" => $this->get('router')->generate('get_dream', ['slug' => $dream->getSlug()], true),
        ]);
        return $restView;
    }

    /**
     * Update existing dream from the submitted data or create a new dream at a specific location.
     *
     * @ApiDoc(
     * resource = true,
     * description = "Create/Update single dream",
     * parameters={
     *          {"name"="title", "dataType"="string", "required"=true, "description"="Dream name"},
     *          {"name"="description", "dataType"="string", "required"=true, "description"="Description about dream"},
     *          {"name"="phone", "dataType"="integer", "required"=true, "description"="Phone number", "format"="(xxx) xxx xxx xxx"},
     *          {"name"="dreamFinancialResources", "dataType"="array<AppBundle\Document\EquipmentResource>", "required"=true, "description"="Equipment resources"},
     *          {"name"="dreamWorkResources", "dataType"="array<AppBundle\Document\WorkResource>", "required"=true, "description"="Work resources"},
     *          {"name"="dreamFinancialResources", "dataType"="array<AppBundle\Document\FinancialResource>", "required"=true, "description"="Financial resources"}
     *      },
     *      section="Put Dream",
     *          statusCodes = {
     *          200 = "Dream successful update",
     *          404 = "Return when dream with current slug not isset"
     *          }
     * )
     *
     *
     * @param  Request $request the request object
     * @param  string  $slug    the page id
     * @return mixed
     */
    public function putDreamAction(Request $request, $slug)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $dreamOld = $em->getRepository('GeekhubDreamBundle:Dream')
            ->findOneBySlug($slug);
        $data = $this->get('serializer')->serialize($data, 'json');
        $dreamNew = $this->get('serializer')->deserialize($data, 'Geekhub\DreamBundle\Entity\Dream', 'json');
        $view = View::create();
        if (!$dreamOld) {
            $dreamNew->setAuthor($this->getUser());
            $em->persist($dreamNew);
            $em->flush();
            $view->setStatusCode(404);
        } else {
            $this->get('services.object_updater_class')->updateObject($dreamOld, $dreamNew);
            $em->flush();
            $view->setStatusCode(200);
        }
        return $view;
    }
}
