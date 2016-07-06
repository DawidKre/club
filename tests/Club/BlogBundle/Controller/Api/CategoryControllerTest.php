<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 20.05.16
 * Time: 14:03
 */

namespace tests\Club\BlogBundle\Controller\Api;


use GuzzleHttp\Client;


class CategoryControllerTest extends \Test\ApiTestCase
{
    public function testPOST()
    {

        $name = 'Category'.rand(0, 999);
        $data = array(
            'name' => $name,
        );
        
        // 1) POST to create category
        $response = $this->client->post('/api/categories', [
            'body'  => json_encode($data)
        ]);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('name', $finishedData);
        
    }

}
