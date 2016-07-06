<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 18.04.16
 * Time: 18:35
 */

namespace Club\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;
use Faker\Provider\Base as BaseProvider;

class LoadFixtureData extends BaseProvider
{


    public function roles()
    {
        $names = ['ROLE_ADMIN', 'ROLE_USER'];

        return $names[array_rand($names)];
    }

}