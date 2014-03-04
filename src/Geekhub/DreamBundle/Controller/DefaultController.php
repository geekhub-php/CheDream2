<?php

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geekhub\DreamBundle\Entity\Dream;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\MediaBundle\Entity\Media;
use Sonata\MediaBundle\Entity\MediaManager;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekhubDreamBundle:Default:index.html.twig');
    }
}
