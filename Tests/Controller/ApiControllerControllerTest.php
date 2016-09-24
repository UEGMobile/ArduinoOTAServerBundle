<?php

namespace UEGMobile\ArduinoOTAServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerControllerTest extends WebTestCase
{
    public function testUpdatebinary()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/updateBinary');

        $this->assertNotEqual(500, $client->getResponse()->getStatusCode());
    }

}
