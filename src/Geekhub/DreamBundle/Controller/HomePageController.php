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
        $completedDreams = $this->getDoctrine()->getRepository('GeekhubDreamBundle:Dream')->findBy(array('currentStatus' => Status::SUCCESS), array('updatedAt' => 'DESC'));

        return array(
            'completedDreams' => $completedDreams
        );
    }
}
