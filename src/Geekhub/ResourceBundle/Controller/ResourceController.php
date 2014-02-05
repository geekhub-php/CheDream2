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
}
