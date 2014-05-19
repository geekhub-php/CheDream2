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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxDreamController extends Controller
{
    public function dreamImageLoaderAction(Request $request)
    {
        $file = $request->files->get('files');

        $imageHandler = $this->get('dream_file_uploader');
        $imageHandler->init($file);
        $result = $imageHandler->loadFiles();

        return new Response(json_encode($result));
    }

    public function dreamCompletedPicturesLoaderAction(Request $request)
    {
        $file = $request->files->get('imgUpl');

        $imageHandler = $this->get('dream_file_uploader');
        $imageHandler->init($file);
        $result = $imageHandler->loadCompletedPictures();

        return new Response(json_encode($result));
    }

    public function dreamPosterLoaderAction(Request $request)
    {
        $file = $request->files->get('dream-poster');

        $imageHandler = $this->get('dream_file_uploader');
        $imageHandler->init($file);
        $result = $imageHandler->loadPoster();

        return new Response(json_encode($result));
    }

    public function dreamPictureRemoveAction(Request$request)
    {
        $mediaId = $request->get('id');
        $mediaManager = $this->get('sonata.media.manager.media');
        $media = $mediaManager->findOneBy(array('id' => $mediaId));
        $mediaManager->delete($media);

        return new Response('ok');
    }

    public function addDreamToFavoriteAction(Request $request)
    {
        $dreamId = $request->get('id');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var Dream $dream */
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneById($dreamId);
        $dream->addUsersWhoFavorite($user);
        $em->persist($dream);
        $em->flush();

        return new Response("Added to favorite with DreamId=$dreamId and UserId=".$user->getId());
    }

    public function removeDreamFromFavoriteAction(Request $request)
    {
        $dreamId = $request->get('id');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var Dream $dream */
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneById($dreamId);
        $dream->removeUsersWhoFavorite($user);
        $em->persist($dream);
        $em->flush();

        return new Response("Removed from favorite with DreamId=$dreamId and UserId=".$user->getId());
    }

    public function removeSomeContributeAction(Request $request)
    {
        $dreamId = $request->get('dreamId');
        $userId = $request->get('userId');
        $resourceId = $request->get('resourceId');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();

        switch ($type) {
            case 'financial':
                $contributions = $em->getRepository('GeekhubDreamBundle:FinancialContribute')->findBy(array(
                    'financialResource' => $resourceId,
                    'user' => $userId,
                    'dream' => $dreamId
                ));
                break;
            case 'equipment':
                $contributions = $em->getRepository('GeekhubDreamBundle:EquipmentContribute')->findBy(array(
                    'equipmentResource' => $resourceId,
                    'user' => $userId,
                    'dream' => $dreamId
                ));
                break;
            case 'work':
                $contributions = $em->getRepository('GeekhubDreamBundle:WorkContribute')->findBy(array(
                    'workResource' => $resourceId,
                    'user' => $userId,
                    'dream' => $dreamId
                ));
                break;
            case 'other':
                $contributions = $em->getRepository('GeekhubDreamBundle:OtherContribute')->findBy(array(
                    'id' => $resourceId,
                    'user' => $userId,
                    'dream' => $dreamId
                ));
                break;
            default:

                return new Response('empty');
        }

        foreach($contributions as $contribute)
        {
            $em->remove($contribute);
        }

        $em->flush();

        return new Response('Removed');
    }
}
