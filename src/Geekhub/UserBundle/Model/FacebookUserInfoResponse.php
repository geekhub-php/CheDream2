<?php

namespace Geekhub\UserBundle\Model;

use Geekhub\UserBundle\Model\VkontakteRequestUserData;
use JMS\Serializer\Annotation\Type;

class FacebookUserInfoResponse
{
    /**
     * @Type("integer")
     */
    protected $id;

    /**
     * @Type("string")
     */
    protected $name;

    /**
     * @Type("string")
     */
    protected $first_name;

    /**
     * @Type("string")
     */
    protected $last_name;

    /**
     * @Type("string")
     */
    protected $link;

    /**
     * @Type("string")
     */
    protected $birthday;

    /**
     * @Type("string")
     */
    protected $gender;

    /**
     * @Type("string")
     */
    protected $email;

    /**
     * @Type("string")
     */
    protected $website;

    /**
     * @Type("integer")
     */
    protected $timezone;

    /**
     * @Type("array")
     */
	protected $response;

	public function getBirthday()
	{
		return $this->birthday;
	}
	//"locale":"en_US","languages":[{"id":"112624162082677","name":"Russian"},{"id":"286242804790413","name":"\u0423\u043a\u0440\u0430\u0438\u043d\u0441\u043a\u0438\u0439."},{"id":"106059522759137","name":"English"}],"verified":true,"updated_time":"2013-11-22T15:58:44+0000","username":"dmitry.chabanenko"}
}