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
     * Gets all User,
     *
     * @ApiDoc(
     * resource = true,
     * description = "Gets all User",
     * output = "AppBundle\Document\Dream",
     * statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the Dream is not found"
     * },
     * section="All Users"
     * )
     *
     *
     * RestView()
     * @param
     * @return View
     *
     * @throws NotFoundHttpException when not exist
     */
    public function getUserAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('GeekhubUserBundle:User')->findAll();

        return $user;
    }
}