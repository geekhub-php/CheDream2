<?php

namespace Geekhub\UserBundle\Model;

use JMS\Serializer\Annotation\Type;

class OdnoklassnikiPhotoResponse
{
    /**
     * @Type("array")
     */
    protected $photoArray;

    /**
     * @Type("array")
     */
    protected $photos;

    public function getPhoto()
    {
        return $this->photos[0]['standard_url'];
    }
}
