<?php

namespace Geekhub\UserBundle\Controller\Api;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\View\View;

class UserController extends FOSRestController
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
     * @param
     * @return mixed
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getUserAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $dream = $manager->getRepository('GeekhubUserBundle:User')->findAll();
        $restView = View::create();
        $restView->setData($dream);
        return $restView;
    }
}