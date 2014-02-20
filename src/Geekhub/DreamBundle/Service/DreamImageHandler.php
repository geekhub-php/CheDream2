<?php
/**
 * Created by PhpStorm.
 * User: Yuriy Tarnavskiy
 * Date: 18.02.14
 * Time: 23:14
 */

namespace Geekhub\DreamBundle\Service;

class DreamImageHandler
{
    public function load()
    {
        $uploader = new FileUploader();
        $uploader->setPicturesTypeAllowed(array('jpg', 'jpeg', 'png', 'gif'));
        $uploader->setFileTypeAllowed(array('doc', 'docx', 'pdf', 'xls', 'xlsx'));
        $uploader->setPictureSizeAllowed(2 * 1024 * 1024);
        $uploader->setFileSizeAllowed(3 * 1024 * 1024);
        $uploader->setUploadPathForFiles('upload/files/');
        $uploader->setUploadPathForPictures('upload/image/');
        $response = $uploader->init();

//        return json_encode($response);
        return $response;
    }
}