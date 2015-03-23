<?php

namespace Geekhub\DreamBundle\Tests\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DreamControllerTest extends WebTestCase
{
    public function testGetDreams()
    {
        $client = static::createClient();
        $container = self::$kernel->getContainer();

        $crawler = $client->request('GET', $container->get('router')->generate('api_v1_get_dreams'));

        $this->assertTrue($crawler->filter('img')->count() > 0);

        $crawler = $client->request('GET', $container->get('router')->generate('api_v1_get_dreams', array('statuses' => array('rejected'))));

        $this->assertFalse($crawler->filter('img')->count() > 0);
    }
}
