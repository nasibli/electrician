<?php

namespace Tests\AppBundle\Controller\Api;

use Tests\AppBundle\AbstractTestCase;

/**
 * Description of ResultControllerTest
 *
 * @author nasibli
 */
class ResultControllerTest extends AbstractTestCase {
    
    public function setUp() {
        parent::setUp();
        
        $this->loadFixtures();
    }

    public function testPostAction() {
        //чтобы не проходить игру каждый раз, просто проставим кол-во шагов
        $sessionService = $this->client->getContainer()->get('session');
        
        //правильные данные
        $result = $this->postResult($sessionService, 'Victor', 4);
        $this->assertTrue($result['success']);
        
        //неправильное количество шагов
        $result = $this->postResult($sessionService, 'Kate', 2);
        $this->assertFalse($result['success']);
        if ($this->assertArrayHasKey('errors',$result)) {
            $this->assertArrayHasKey('stepCount', $result['errors']);
        }
        
        //неправильное имя пользоявателя
        $result = $this->postResult($sessionService, '', 10);
        $this->assertFalse($result['success']);
        if ($this->assertArrayHasKey('errors',$result)) {
            $this->assertArrayHasKey('userName', $result['errors']);
        }
        
        //сбросим в исходное состояние
        $sessionService->set('stepCount', 0);
    }
    
    public function testGetAction() {
        $this->client->request('GET', '/result');
        $results = json_decode( $this->client->getResponse()->getContent(), true );
        
        //смотрим, если 10 результатов
        if ($this->assertArrayHasKey('results', $results)) {
            if ($this->assertCount(10, $results['results'])) {
                //проверяем сортировку
                for($resultIndex = 1; $resultIndex <= 9; $resultIndex++) {
                    $stepCurrent = $results['results'][$resultIndex]['stepCount'];
                    $stepPrev    = $results['results'][$resultIndex-1]['stepCount'];
                    $this->assertGreaterThanOrEqual($stepCurrent, $stepPrev);
                } 
            }
        }
        
    }    
    
    private function postResult ($sessionService, $userName, $stepCount) {
        $sessionService->set('stepCount', $stepCount);
        $this->client->request('POST', '/result', ['userName' => $userName]);
        $content = $this->client->getResponse()->getContent();
        return json_decode($content, true);
    }
        
}
