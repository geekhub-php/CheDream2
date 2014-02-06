<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.02.14
 * Time: 17:28
 */

namespace Geekhub\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResourceController extends Controller
{
    public function indexAction()
    {
        return $this->render('GeekhubResourceBundle:Resource:index.html.twig');
    }

    public function profileAction()
        {
            return $this->render('GeekhubResourceBundle:Resource:profile.html.twig');
        }

    public function allDreamsAction()
    {
        return $this->render('GeekhubResourceBundle:Resource:allDreams.html.twig');
    }
}
