<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:10
 */

namespace Geekhub\DreamBundle\Controller;

use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxDreamController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dreamImageLoaderAction(Request $request)
    {
        return $this->loadeFile($request->files->get('files'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dreamCompletedPicturesLoaderAction(Request $request)
    {
        return $this->loadeFile($request->files->get('imgUpl'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dreamPosterLoaderAction(Request $request)
    {
        return $this->loadeFile($request->files->get('dream-poster'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function dreamPictureRemoveAction(Request$request)
    {
        $mediaId = $request->get('id');
        $mediaManager = $this->get('sonata.media.manager.media');
        $media = $mediaManager->findOneBy(array('id' => $mediaId));
        $mediaManager->delete($media);

        return new Response('ok');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addDreamToFavoriteAction(Request $request)
    {
        $user = $this->getUser();
        $dream = $this->getDreamFromRequest($request);
        $dream->addUsersWhoFavorite($user);

        $this->getDoctrine()->getManager()->flush();

        return new Response("Added to favorite with DreamId={$dream->getId()} and UserId={$user->getId()}");
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function removeDreamFromFavoriteAction(Request $request)
    {
        $user = $this->getUser();
        $dream = $this->getDreamFromRequest($request);
        $dream->removeUsersWhoFavorite($user);

        $this->getDoctrine()->getManager()->flush();

        return new Response("Removed from favorite with DreamId={$dream->getId()} and UserId={$user->getId()}");
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function removeSomeContributeAction(Request $request)
    {
        $dreamId = $request->get('dreamId');
        $userId = $request->get('userId');
        $resourceId = $request->get('resourceId');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();
        $acceptedTypes = array(
            'financial' => 'financialResource',
            'equipment' => 'equipmentResource',
            'work'      => 'workResource',
            'other'     => 'id',
        );

        if (!in_array($type, $acceptedTypes)) {
            return new Response('empty');
        }

        $type = ucfirst($type);
        $contributions = $em->getRepository("GeekhubDreamBundle:{$type}Contribute")->findBy(array(
            $acceptedTypes[$type] => $resourceId,
            'user' => $userId,
            'dream' => $dreamId
        ));

        foreach($contributions as $contribute)
        {
            $em->remove($contribute);
        }

        $em->flush();

        return new Response('Removed');
    }

    /**
     * @param $file
     * @return JsonResponse
     */
    private function loadeFile($file)
    {
        $imageHandler = $this->get('dream_file_uploader');
        $imageHandler->init($file);
        $result = $imageHandler->loadFiles();

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @return Dream
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    private function getDreamFromRequest(Request $request)
    {
        $dreamId = $request->get('id');

        if (!$dreamId) {
            throw new \Exception('Not defined id for dream add/remove from favorite actions');
        }

        $dream = $this->getDoctrine()
            ->getRepository('GeekhubDreamBundle:Dream')
            ->find($dreamId)
        ;

        if (!$dream) {
            throw new NotFoundHttpException("Not found dream with id {$dreamId}");
        }

        return $dream;
    }
}
