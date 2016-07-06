<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 20.05.16
 * Time: 14:35
 */

namespace Test;


use GuzzleHttp\Client;

class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    private static $staticClient;

    /**
     * @var Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client([
            'base_uri' => 'http://localhost:8000',
            'http_errors' => false
        ]);
    }

    protected function setUp()
    {
        $this->client = self::$staticClient;
    }


}