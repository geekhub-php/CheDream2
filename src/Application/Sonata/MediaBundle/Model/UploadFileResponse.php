<?php

namespace Application\Sonata\MediaBundle\Model;

use Geekhub\ResourceBundle\Model\PropertyAccessor;

class UploadFileResponse
{
    use PropertyAccessor;

    protected $errors;
    protected $preview;
}
