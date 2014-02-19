<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:10
 */

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UploadHandler;

class AjaxDreamController extends Controller
{
    public function DreamImageLoaderAction()
    {
//        $handler = new UploadHandler();
        $imageHandler = $this->get('dream_loader_images');

//        $imageHandler->generate_response();

//        var_dump($imageHandler);

//        return $handler;
        return $imageHandler;

    }

} 