<?php

namespace Msports\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MsportsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
