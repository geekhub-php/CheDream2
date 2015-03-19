<?php

namespace Geekhub\DreamBundle\Controller\Api;


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
     * Gets Dreams by status,.
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets Dreams by status",
     * output =   { "class" = "AppBundle\Document\Dream" },
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the status is not found"
     * }
     * )
     *
     * RestView()
     *
     * @QueryParam(name="status", strict=true, requirements="[a-z]+", description="Status", nullable=true)
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Count statuses at one page")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number of page to be shown")
     * @QueryParam(name="sort_by", strict=true, requirements="^[a-zA-Z]+", default="createdAt", description="Sort by", nullable=true)
     * @QueryParam(name="sort_order", strict=true, requirements="^[a-zA-Z]+", default="DESC", description="Sort order", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return View
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getDreamsAction(ParamFetcher $paramFetcher)
    {
        $repository = $this->getDoctrine()->getManager()
                            ->getRepository('GeekhubDreamBundle:Dream')
        ;

        if (!$paramFetcher->get('status')) {
            $queryBuilder = $repository->createQueryBuilder('dream')
                                    ->sort($paramFetcher->get('sort_by'), $paramFetcher->get('sort_order'))
                                    ->field('dream.currentStatus')
                                    ->notEqual('fail')
                                    ->getQuery()
                                    ->execute()
                                    ->toArray()
            ;
        } else {
            $queryBuilder = $repository->createQueryBuilder('dream')
                                    ->sort($paramFetcher->get('sort_by'), $paramFetcher->get('sort_order'))
                                    ->field('currentStatus')
                                    ->equals($paramFetcher->get('status'))
                                    ->getQuery()
                                    ->execute()
                                    ->toArray()
            ;
        }

        $dreamsResponse = new DreamsResponse();
        $dreamsResponse->setSortOrder($paramFetcher->get('sort_order'));

        $paginator = new Pagerfanta(new ArrayAdapter($queryBuilder));
        $paginator
            ->setMaxPerPage($paramFetcher->get('limit'))
            ->setCurrentPage($paramFetcher->get('page'))
        ;

        $dreamsResponse->setDreams($paginator->getCurrentPageResults());
        $dreamsResponse->setPageCount($paginator->getNbPages());

        $nextPage = $paginator->hasNextPage() ?
            $this->generateUrl('get_dreams', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')+1,
                )
            ) :
            'false';

        $previsiousPage = $paginator->hasPreviousPage() ?
            $this->generateUrl('get_dreams', array(
                    'limit' => $paramFetcher->get('limit'),
                    'page' => $paramFetcher->get('page')-1,
                )
            ) :
            'false';

        $dreamsResponse->setNextPage($nextPage);
        $dreamsResponse->setPreviousPage($previsiousPage);

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

    /**
     * Create dream
     *
     * @ApiDoc(
     *      resource = true,
     *      description = "Create single dream",
     *      input = "Geekhub\DreamBundle\Entity\Dream",
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

        $user = $this->getUser();

        $dream = $this->get('serializer')
                      ->deserialize($request->getBody(), 'Geekhub\DreamBundle\Entity\Dream', 'json')
        ;

        if ($errors = $this->get('validator')->validate($dream)) {
            throw new BadRequestHttpException($errors);
        }

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
     * input = "Geekhub\DreamBundle\Entity",
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
                        ->findOneBySlug($slug)
        ;

        $dreamNew = $this->get('serializer')
                        ->deserialize($request->getBody(), 'Geekhub\DreamBundle\Entity\Dream', 'json')
        ;

        $view = View::create();

        if ($errors = $this->get('validator')->validate($dreamNew) || $errors = $this->get('validator')->validate($dreamOld)) {
            throw new BadRequestHttpException($errors);
        }

        if (!$dreamOld) {
            $dreamNew->setAuthor($this->getUser());

            $dm->persist($dreamNew);
            $dm->flush();

            $view->setStatusCode(404);
        } else {
            $this->get('app.services.object_updater')->updateObject($dreamOld, $dreamNew);

            $dm->flush();

            $view->setStatusCode(200);
        }

        return $view;
    }
}
