<?php

namespace Application\Sonata\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Controller\Annotations\View,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Application\Sonata\MediaBundle\Model\UploadFileResponse;

class ApiController extends Controller
{
    /**
     * @ApiDoc(
     * description="Upload files. Allow to load - images, docs(pdf, doc, xls, etc.)",
     * statusCodes={
     *      200="Returned when file was correct load to the server",
     *      401="Returned when user not authorized",
     *      403="Returned if file type or file size not allowed",
     *      500="Returned if something went wrong",
     *  },
     *  section="Internal api",
     *  output="Application\Sonata\MediaBundle\Model\UploadFileResponse"
     * )
     *
     * @param Request $request
     * @View()
     *
     * @return Response
     */
    public function uploadFilesAction(Request $request)
    {
        $response = new UploadFileResponse();

//        if (!$this->getUser()) {
//            $response->setErrors(array('User Unauthorized!'));
//
//            return new Response($this->container->get('jms_serializer')->serialize($response, 'json'), 401);
//        }
//
//        $response = $this->container->get('file_uploader')->loadFile();

        return array('errors' => array('bad size'), 'previews' => array('http://myhost.com/uploads/someimg.jpg'));
    }
}
