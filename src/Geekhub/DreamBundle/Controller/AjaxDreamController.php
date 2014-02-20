<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:10
 */

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxDreamController extends Controller
{
    public function DreamImageLoaderAction()
    {
        $imageHandler = $this->get('dream_file_uploader');

        $result['json'] = $imageHandler->load();

        return new Response(json_encode($result));
    }
}