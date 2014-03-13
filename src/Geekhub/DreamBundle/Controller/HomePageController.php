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
use Geekhub\DreamBundle\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomePageController extends Controller
{
    /**
     * @View()
     */
    public function homeAction()
    {
        $completedDreams = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')
            ->getArrayDreamsByStatus(Status::SUCCESS);

//        var_dump($completedDreams); exit;

        $dreams = $this->getDoctrine()->getManager()->getRepository('GeekhubDreamBundle:Dream')->findAll();

        return array(
            'dreams' => $dreams,
            'completedDreams' => $completedDreams
        );
    }
}
