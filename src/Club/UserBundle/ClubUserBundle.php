<?php

namespace Club\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ClubUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
