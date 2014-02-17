<?php

namespace Geekhub\UserBundle\Model;

use Geekhub\UserBundle\Model\VkontakteRequestUserData;
use JMS\Serializer\Annotation\Type;

class VkontakteResponse
{
    /**
     * @Type("array")
    */
	protected $response;

	public function getResponse()
	{
		return $this->response;
	}
}