<?php

namespace Geekhub\AdminUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GeekhubAdminUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
