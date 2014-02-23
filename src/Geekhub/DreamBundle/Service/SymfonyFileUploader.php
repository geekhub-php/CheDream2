<?php
/**
 * Created by PhpStorm.
 * File: SymfonyFileUploader.php
 * User: Yuriy Tarnavskiy
 * Date: 21.02.14
 * Time: 10:37
 */

namespace Geekhub\DreamBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class SymfonyFileUploader
{
    protected $file;
    protected $liip;
    protected $fileType;
    protected $fileName;
    protected $fileSize;
    protected $response;
    protected $uploadPath;
    protected $uploadPathForPictures;
    protected $uploadPathForFiles;
    protected $allowedSizeForPictures;
    protected $allowedSizeForFile;
    protected $allowedPictureTypes;
    protected $allowedFilesTypes;

    public function __construct(UploadedFile $file, CacheManager $cacheManager)
    {
        $this->response = array();
        $this->file = $file;
        $this->liip = $cacheManager;
        $this->setData();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $uploadPath
     */
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    /**
     * @return mixed
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * @param mixed $allowedSizeForFile
     */
    public function setAllowedSizeForFile($allowedSizeForFile)
    {
        $this->allowedSizeForFile = $allowedSizeForFile;
    }

    /**
     * @return mixed
     */
    public function getAllowedSizeForFile()
    {
        return $this->allowedSizeForFile;
    }

    /**
     * @param mixed $allowedSizeForPictures
     */
    public function setAllowedSizeForPictures($allowedSizeForPictures)
    {
        $this->allowedSizeForPictures = $allowedSizeForPictures;
    }

    /**
     * @return mixed
     */
    public function getAllowedSizeForPictures()
    {
        return $this->allowedSizeForPictures;
    }

    /**
     * @param mixed $uploadPathForFiles
     */
    public function setUploadPathForFiles($uploadPathForFiles)
    {
        $this->uploadPathForFiles = $uploadPathForFiles;
    }

    /**
     * @return mixed
     */
    public function getUploadPathForFiles()
    {
        return $this->uploadPathForFiles;
    }

    /**
     * @param mixed $uploadPathForPictures
     */
    public function setUploadPathForPictures($uploadPathForPictures)
    {
        $this->uploadPathForPictures = $uploadPathForPictures;
    }

    /**
     * @param mixed $allowedFilesTypes
     */
    public function setAllowedFilesTypes($allowedFilesTypes)
    {
        $this->allowedFilesTypes = $allowedFilesTypes;
    }

    /**
     * @return mixed
     */
    public function getAllowedFilesTypes()
    {
        return $this->allowedFilesTypes;
    }

    /**
     * @param mixed $allowedPictureTypes
     */
    public function setAllowedPictureTypes($allowedPictureTypes)
    {
        $this->allowedPictureTypes = $allowedPictureTypes;
    }

    /**
     * @return mixed
     */
    public function getAllowedPictureTypes()
    {
        return $this->allowedPictureTypes;
    }

    /**
     * @return mixed
     */
    public function getUploadPathForPictures()
    {
        return $this->uploadPathForPictures;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setData()
    {
//        $randInt = mt_rand(1, 999999);
        $this->fileType = $this->file->getMimeType();
//        $this->fileName = $randInt.'-'.$this->file->getClientOriginalName();
        $this->fileName = $this->file->getClientOriginalName();
        $this->fileSize = $this->file->getSize();
    }

    public function load()
    {
        if($this->check($this->getFileName(), $this->getFileSize())) {
            $this->file->move($this->getUploadPath(), $this->getFileName());
        }

        return $this->getResponse();
    }

    protected function check($file, $size)
    {
        $rash = $this->get_mimetype($file);

        if(in_array($rash, $this->getAllowedPictureTypes())) {
            if($size < $this->getAllowedSizeForPictures()) {
                $this->setUploadPath($this->getUploadPathForPictures());
                $srcThumb = $this->liip->getBrowserPath($this->getUploadPath().$file, 'dream_thumb', true);
                $this->response[] = array(
                    'type'          =>  'image',
                    'srcPreview'    =>  $srcThumb,
                    'src'           =>  $this->getUploadPath().$file,
                    'name'          =>  $file,
                    'error'         =>  null
                );

                return true;
            }   else {
                $this->response[] = array(
                    'type'          =>  'image',
                    'srcPreview'    =>  null,
                    'src'           =>  $file,
                    'error'         =>  'файл зображення занадто великий.'
                );

                return false;
            }
        }
        if (in_array($rash, $this->getAllowedFilesTypes())) {
            if($size < $this->getAllowedSizeForFile()) {
                $this->setUploadPath($this->getUploadPathForFiles());
                $this->response[] = array(
                    'type'          =>  'file',
                    'srcPreview'    =>  null,
                    'src'           =>  $this->getUploadPath().$file,
                    'name'          =>  $file,
                    'error'         =>  null
                );

                return true;
            }   else {
                $this->response[] = array(
                    'type'          =>  'file',
                    'srcPreview'    =>  null,
                    'src'           =>  $file,
                    'error'         =>  'файл занадто великий.'
                );

                return false;
            }
        }

        $this->response[] =array(
            'type'          => 'file',
            'srcPreview'    =>  null,
            'src'           => $file,
            'error'         => 'недопустимий тип файлу.'
        );

        return false;
    }

    protected function get_mimetype($filename)
    {
        $type = strtolower(preg_replace('/^.*\./','',$filename));

        return $type;
    }
}