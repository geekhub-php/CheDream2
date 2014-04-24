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
        $action = $request->get('state');
        if (null ==$action) {
            $action = 'toggle';
        }
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        /** @var Dream $dream */
        $dream = $em->getRepository('GeekhubDreamBundle:Dream')->findOneById($dreamId);
        if ($action == 'add') {
            $dream->addUsersWhoFavorite($user);
            $action = "Added to favorite";
        }

        if ($action == 'remove') {
            $dream->removeUsersWhoFavorite($user);
            $action = "Removed from favorite";
        }

        if ($action == 'toggle') {
            if ($dream->getUsersWhoFavorites()->contains($user)) {
                $dream->removeUsersWhoFavorite($user);
                $action = "Removed from favorite";
            }
            else {
                $dream->addUsersWhoFavorite($user);
                $action = "Added to favorite";
            }
        }
        $em->persist($dream);
        $em->flush();

        return new Response($action." with DreamId=$dreamId and UserId=".$user->getId());
    }
}
