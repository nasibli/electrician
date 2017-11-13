<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\AbstractTestCase;

class DefaultControllerTest extends AbstractTestCase
{
    public function testIndexAction()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $this->assertContains('Электрик', $crawler->filter('h1')->text());
        $this->assertContains('Лучшие результаты', $crawler->filter('#btn_best')->text());
        $this->assertTrue( !empty($crawler->filter('#result_window')->text()) );
        $this->assertTrue( !empty($crawler->filter('#best_window')->text()) );
    }
}
