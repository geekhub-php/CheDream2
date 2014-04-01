<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:10
 */

namespace Geekhub\DreamBundle\Controller;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\Status;
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

    public function loadMoreDreamsAction(Request $request)
    {
        $offset = $request->get('offset');
        $limit = $this->container->getParameter('count_dreams_on_home_page');
        $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')
            ->getDreamsByTwoStatuses(Status::COLLECTING_RESOURCES, Status::IMPLEMENTING, $limit, $offset);

        return $this->render('GeekhubDreamBundle:includes:homePageLoadDream.html.twig', array(
            'dreams' => $dreams,
        ));
    }

    public function loadFilteredDreamsForAdminAction(Request $request)
    {
        $statusCode = $request->get('status');

        if ($statusCode == "ALL") {
            $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findAll();
        } else {
            $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findBy(array('currentStatus' => $statusCode));
        }

        return $this->render("GeekhubDreamBundle:includes:showFilteredDreamByStatus.html.twig", array('dreams' => $dreams));
    }
}
