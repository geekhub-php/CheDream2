<?php

namespace Geekhub\UserBundle\Model;

use JMS\Serializer\Annotation\Type;

class VkontakteResponse
{
    /**
     * @Type("array")
     */
    protected $response;

    public function getResponse($field)
    {
        return $this->response[0][$field];
    }
}
