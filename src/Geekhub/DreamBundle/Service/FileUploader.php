<?php
/**
 * Created by PhpStorm.
 * File: FileUploader.php
 * User: Yuriy Tarnavskiy
 * Date: 20.02.14
 * Time: 15:43
 */

namespace Geekhub\DreamBundle\Service;

class FileUploader
{
    protected $fileName;
    protected $fileTmpName;
    protected $fileType;
    protected $fileSize;
    protected $filePreviewPath;
    protected $fileOriginalPath;
    protected $fileError;
    protected $pathToFile;
    protected $pathToPreview;
    protected $picturesTypeAllowed;
    protected $fileTypeAllowed;
    protected $uploadPath;
    protected $pictureSizeAllowed;
    protected $fileSizeAllowed;
    protected $responses;
    protected $uploadPathForPictures;
    protected $uploadPathForFiles;

    public function __construct()
    {
        $this->responses = array();
    }

    /**
     * @param mixed $uploadPathForFiles
     */
    public function setUploadPathForFiles($uploadPathForFiles)
    {
        $this->checkPath($uploadPathForFiles);

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
        $this->checkPath($uploadPathForPictures);

        $this->uploadPathForPictures = $uploadPathForPictures;
    }

    /**
     * @return mixed
     */
    public function getUploadPathForPictures()
    {
        return $this->uploadPathForPictures;
    }

    /**
     * @param mixed $picturesTypeAllowed
     */
    public function setPicturesTypeAllowed($picturesTypeAllowed)
    {
        $this->picturesTypeAllowed = $picturesTypeAllowed;
    }

    /**
     * @param mixed $fileTypeAllowed
     */
    public function setFileTypeAllowed($fileTypeAllowed)
    {
        $this->fileTypeAllowed = $fileTypeAllowed;
    }

    /**
     * @return mixed
     */
    public function getFileSizeAllowed()
    {
        return $this->fileSizeAllowed;
    }

    /**
     * @return mixed
     */
    public function getFileTypeAllowed()
    {
        return $this->fileTypeAllowed;
    }

    /**
     * @return mixed
     */
    public function getPictureSizeAllowed()
    {
        return $this->pictureSizeAllowed;
    }

    /**
     * @return mixed
     */
    public function getPicturesTypeAllowed()
    {
        return $this->picturesTypeAllowed;
    }

    /**
     * @param mixed $pictureSizeAllowed
     */
    public function setPictureSizeAllowed($pictureSizeAllowed)
    {
        $this->pictureSizeAllowed = $pictureSizeAllowed;
    }

    /**
     * @param mixed $fileSizeAllowed
     */
    public function setFileSizeAllowed($fileSizeAllowed)
    {
        $this->fileSizeAllowed = $fileSizeAllowed;
    }

    /**
     * @param mixed $fileError
     */
    public function setFileError($fileError)
    {
        $this->fileError = $fileError;
    }

    /**
     * @return mixed
     */
    public function getFileError()
    {
        return $this->fileError;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileOriginalPath
     */
    public function setFileOriginalPath($fileOriginalPath)
    {
        $this->fileOriginalPath = $fileOriginalPath;
    }

    /**
     * @return mixed
     */
    public function getFileOriginalPath()
    {
        return $this->fileOriginalPath;
    }

    /**
     * @param mixed $filePreviewPath
     */
    public function setFilePreviewPath($filePreviewPath)
    {
        $this->filePreviewPath = $filePreviewPath;
    }

    /**
     * @return mixed
     */
    public function getFilePreviewPath()
    {
        return $this->filePreviewPath;
    }

    /**
     * @param mixed $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param mixed $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param mixed $pathToFile
     */
    public function setPathToFile($pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @return mixed
     */
    public function getPathToFile()
    {
        return $this->pathToFile;
    }

    /**
     * @param mixed $pathToPreview
     */
    public function setPathToPreview($pathToPreview)
    {
        $this->pathToPreview = $pathToPreview;
    }

    /**
     * @return mixed
     */
    public function getPathToPreview()
    {
        return $this->pathToPreview;
    }

    /**
     * @param mixed $fileTmpName
     */
    public function setFileTmpName($fileTmpName)
    {
        $this->fileTmpName = $fileTmpName;
    }

    /**
     * @return mixed
     */
    public function getFileTmpName()
    {
        return $this->fileTmpName;
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

    public function checkPath($path)
    {
        if(!file_exists($path)) {
            mkdir($path, 0755);
        }
    }

    public function checkFile($fileName, $fileSize)
    {
        $rash = $this->get_mimetype($fileName);
        if(in_array($rash, $this->getPicturesTypeAllowed())) {
            $this->setUploadPath($this->getUploadPathForPictures());

            if($fileSize < $this->getPictureSizeAllowed()) {
                $this->responses[] =array(
                    'type' => 'image',
                    'src' => $this->getUploadPath().$fileName,
                    'error' => null
                );

                return true;
            } else {
                $this->responses[] =array(
                    'type' => 'image',
                    'src' => $this->getUploadPath().$fileName,
                    'error' => 'the image is too big'
                );

                return false;
            }
        }

        if (in_array($rash, $this->getFileTypeAllowed())) {
            $this->setUploadPath($this->getUploadPathForFiles());
            if($fileSize < $this->getFileSizeAllowed()) {
                $this->responses[] =array(
                    'type' => 'file',
                    'src' => $this->getUploadPath().$fileName,
                    'error' => null
                );

                return true;
            } else {
                $this->responses[] =array(
                    'type' => 'file',
                    'src' => $this->getUploadPath().$fileName,
                    'error' => 'the file is too big'
                );

                return false;
            }
        }

        $this->responses[] =array(
            'type' => 'file',
            'src' => $this->getUploadPath().$fileName,
            'error' => 'the file type is not allowed'
        );

        return false;
    }

    public function init()
    {
        foreach ($_FILES['files']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $randInt = mt_rand(1, 999999);
                $this->setFileName($randInt.'-'.$_FILES['files']['name'][$key]);
                $this->setFileTmpName($_FILES['files']['tmp_name'][$key]);
                $this->setFileSize($_FILES['files']['size'][$key]);
                $this->setFileType($_FILES['files']['type'][$key]);

                if($this->checkFile($this->getFileName(), $this->getFileSize())) {

                    move_uploaded_file($this->getFileTmpName(), $this->getUploadPath().$this->getFileName());
                }
            } else {
                $this->responses[] =array(
                    'type' => 'file',
                    'src' => $_FILES['files']['name'][$key],
                    'error' => 'the file is not loaded'
                );
            }
        }
        $response = $this->getResponses();

        return $response;
    }

    public function get_mimetype($filename)
    {
        $type = strtolower(preg_replace('/^.*\./','',$filename));

        return $type;
    }

    public function getResponses()
    {
        return $this->responses;
    }

} 