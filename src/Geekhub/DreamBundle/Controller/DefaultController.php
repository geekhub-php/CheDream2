<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\MediaBundle\Entity\MediaManager;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
//        $files = $request->files->get('fileUpload');
//
//        $dream = new Dream();
//        $dream->setTitle('Blablabla');
//        $dream->setDescription('Blablabla');
//        $dream->setMainPicture('Blablabla');
//        $dream->setPhone('Blablabla');
//        $dream->setExpiredDate(new \DateTime());
//
//        $media = new Media;
//        $media->setBinaryContent($files->getRealPath());
//        $media->setProviderName('sonata.media.provider.image');
//
//        $mediaManager = $this->get('sonata.media.manager.media');
//        $mediaManager->save($media);
//
//        $dream->addMedia($media);
//        $this->getDoctrine()->getManager()->persist($dream);
//        $this->getDoctrine()->getManager()->flush();
//
//        $files = $dream->getMedia();
//
//        var_dump($files->first()); exit;
        return $this->render('GeekhubDreamBundle:Default:index.html.twig');
    }
}
