<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:10
 */

namespace Geekhub\DreamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxDreamController extends Controller
{
    public function DreamImageLoaderAction(Request $request)
    {
//        $file = $request->files->all();
//        var_dump($file);
//        var_dump($_FILES['files']);
//        exit;
////        $file = $request->files->get('files');

//        $imageHandler = $this->get('dream_file_uploader');
//
//
//        $result = $imageHandler->load();
//        var_dump($_POST, $_FILES); exit;

        $result = array(
            array('name' => 'qqq', 'src' => 'www', 'error' => 'eee'),
            array('name' => 'aaa', 'src' => 'sss', 'error' => 'ddd'),
            array('name' => 'zzz', 'src' => 'xxx', 'error' => 'ccc')
        );
        return new Response(json_encode($result));
    }
}