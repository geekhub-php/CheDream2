<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.04.14
 * Time: 21:44
 */

namespace Geekhub\OAuthBunsle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeekhubOAuthBundle extends Bundle
{
    public function getParent()
    {
        return 'HWIOAuthBundle';
    }
}
