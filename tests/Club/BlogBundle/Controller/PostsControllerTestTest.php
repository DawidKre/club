<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 08.04.16
 * Time: 10:37
 */

namespace tests\Club\BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsControllerTestTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Aktualności', $crawler->text());

    }

    public function testPost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/slug');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Aktualności', $crawler->text());

    }

}
