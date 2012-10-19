<?php

namespace Operowo\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OperowoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
