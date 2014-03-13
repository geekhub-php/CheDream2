<?php
/**
 * Created by PhpStorm.
 * File: HomePageController.php
 * User: Yuriy Tarnavskiy
 * Date: 13.03.14
 * Time: 15:38
 */

namespace Geekhub\DreamBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomePageController extends Controller
{
    /**
     * @View(templateVar="dreams")
     */
    public function homeAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findAll();
    }
}
