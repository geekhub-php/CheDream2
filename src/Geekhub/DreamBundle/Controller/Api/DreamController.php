<?php

namespace Geekhub\DreamBundle\Controller\Api;


use Geekhub\DreamBundle\Model\DreamsResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @param ParamFetcher $paramFetcher
     * @return mixed
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getDreamsAction(ParamFetcher $paramFetcher)
    {
        $manager = $this->getDoctrine()->getManager();

        $dreams = $manager->getRepository('GeekhubDreamBundle:Dream')->findAll();

        $selfPage = $this->generateUrl('api_v1_get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page'),
        ));

        $nextPage = $this->generateUrl('api_v1_get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page')+1,
        ));

        $prevPage = $this->generateUrl('api_v1_get_dreams', array(
            'count' => $paramFetcher->get('count'),
            'page' => $paramFetcher->get('page')-1,
        ));

        $firstPage = $this->generateUrl('api_v1_get_dreams', array(
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

        $restView = View::create();
        $restView->setData($dreamsResponse);

        return $restView;
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
     *      }
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
//        $user = $this->getUser();
        $data = $this->get('serializer')->serialize($data, 'json');
        $dream = $this->get('serializer')->deserialize($data, 'Geekhub\DreamBundle\Entity\Dream', 'json');
//        $dream->setAuthor($user);
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
     * },
     * statusCodes = {
     * 200 = "Dream successful update",
     * 404 = "Return when dream with current slug not isset"
     * }
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
        $dm = $this->getDoctrine()->getManager();
        $dreamOld = $dm->getRepository('GeekhubDreamBundle:Dream')
            ->findOneBySlug($slug);
        $data = $this->get('serializer')->serialize($data, 'json');
        $dreamNew = $this->get('serializer')->deserialize($data, 'Geekhub\DreamBundle\Entity\Dream', 'json');
        $view = View::create();
        if (!$dreamOld) {
            $dreamNew->setAuthor($this->getUser());
            $dm->persist($dreamNew);
            $dm->flush();
            $view->setStatusCode(404);
        } else {
            $this->get('services.object_updater_class')->updateObject($dreamOld, $dreamNew);
            $dm->flush();
            $view->setStatusCode(200);
        }
        return $view;
    }
}
