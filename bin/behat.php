#!/usr/bin/env php
<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('BEHAT_BIN_PATH',     __FILE__);

if (is_file($autoload = getcwd() . '/vendor/autoload.php')) {
    require $autoload;
}

if (is_file($autoload = __DIR__ . '/../vendor/autoload.php')) {
    require($autoload);
} elseif (is_file($autoload = __DIR__ . '/../../../autoload.php')) {
    require($autoload);
} else {
    fwrite(STDERR,
        'You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL
    );
    exit(1);
}

$factory = new \Behat\Behat\ApplicationFactory();
$factory->createApplication()->run();
