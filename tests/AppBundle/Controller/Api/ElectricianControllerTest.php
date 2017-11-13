<?php

namespace Tests\AppBundle\Controller\Api;

use Tests\AppBundle\AbstractTestCase;
use AppBundle\Manager\ElectricianManager;

class ElectricianControllerTest extends AbstractTestCase {
         
    public function setUp() {
        parent::setUp();
        
        //переходим на страницу игры, чтобы был инициализация
        $this->client->request('GET', '/');
        
        //в service container подставляем мок ElectricianManager, чтобы от рандома тесты 
        //не падали, хотя не факт, что поможет
        $this->client->getContainer()->set(ElectricianManager::class, $this->getElectricianManagerMock(false));
    }
    
    public function testStepAction () {
        
        foreach ($this->getIncorrectSteps() as [$rowIndex, $colIndex, $result]) {
            $this->assertIncorrectStep($rowIndex, $colIndex, $result);
        }
        
        foreach ($this->getCorrectSteps() as [$rowIndex, $colIndex, $result]) {
            $this->assertCorrectStep($rowIndex, $colIndex, $result);
        }
        
    }
    
    private function assertIncorrectStep($rowIndex, $colIndex, $result) {
        $this->client->request(
            'POST',
            '/electrician',
            ['rowIndex' => $rowIndex, 'colIndex' => $colIndex]    
        );
        
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertTrue(
            $response->headers->contains('Content-type', 'application/json')
        );
     
        $this->assertJsonStringEqualsJsonString($result, $response->getContent());
    }


    private function assertCorrectStep($rowIndex, $colIndex, $result) {
        
        $this->client->request(
            'POST',
            '/electrician',
            ['rowIndex' => $rowIndex, 'colIndex' => $colIndex]    
        );
        
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertTrue(
            $response->headers->contains('Content-type', 'application/json')
        );
     
        $this->assertJsonStringEqualsJsonString($result, $response->getContent());
        
    }
    
    private function getCorrectSteps() {
        return [
            [ 1, 1, '{"cells":{"1":{"2":true,"1":true},"2":{"2":true,"1":true}},"stepCount":1,"isCompleted":false}' ],
            [ 1, 4, '{"cells":{"1":{"3":true,"5":true,"4":true},"2":{"3":true,"5":true,"4":true}},"stepCount":2,"isCompleted":false}'],
            [ 4, 2, '{"cells":{"4":{"1":true,"3":true,"2":true},"3":{"1":true,"3":true,"2":true},"5":{"1":true,"3":true,"2":true}},"stepCount":3,"isCompleted":false}'],
            [ 4, 5, '{"cells":{"4":{"4":true,"5":true},"3":{"4":true,"5":true},"5":{"4":true,"5":true}},"stepCount":4,"isCompleted":true}']
        ];
    }
    
    private function getIncorrectSteps() {
        return [
            [ 0,    0,  '{"errors":{"rowIndex":"This value should be 1 or more.","colIndex":"This value should be 1 or more."}}' ],
            ['a',  'b', '{"errors":{"rowIndex":"This value should be a valid number.","colIndex":"This value should be a valid number."}}' ],
        ];
    }
    
}
