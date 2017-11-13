<?php

namespace Tests\AppBundle\Manager;

use Tests\AppBundle\AbstractTestCase;
use AppBundle\Manager\ElectricianManager;

class ElectricianManagerTest extends AbstractTestCase {
    
    /** @var AppBundle\Manager\ElectricianManager */
    private $electricianManager;

    public function setUp() {
        parent::setUp();
        
        //Получаем мок менеджера, чтобы тесты не падали из-за рандома
        $this->electricianManager = $this->getElectricianManagerMock(false);
    }

    public function testInit() {
        $this->electricianManager->init();
        
        //проверяем доску при инициализации
        $board = $this->electricianManager->getBoard();
        if ($this->assertCount(5, $board)) {
            foreach ($board as $cols) {
                $this->assertCount(5, $cols);
                foreach ($cols as $checked) {
                    $this->assertFalse($checked);
                }
            }
        }
        
        //проверяем количество шагов при инициализации
        $stepCount = $this->electricianManager->getStepCount();
        $this->assertEquals(0, $stepCount);
        
    }
    
    public function testStep() {
        $this->electricianManager->init();
        
        //тесты без рандома
        foreach ($this->getStepData() as [$rowIndex, $colIndex, $stepCount, $result]) {
            $cells = $this->electricianManager->step($rowIndex, $colIndex);
            $this->assertEquals($cells, $result);
            $this->assertEquals($stepCount, $this->electricianManager->getStepCount());
        }
        $this->assertTrue($this->electricianManager->isCompleted());
        
        //тесты с рандомом
        $this->electricianManager = $this->getElectricianManagerMock(true, 5);
        $this->electricianManager->init();
        
        $cells = $this->electricianManager->step(1,1);
        $result = [ 1 => [1 => true, 2 => true], 2 => [1 => true, 2 => true], 5 => [5 => true] ];
        $this->assertEquals($cells, $result);
    }  
    
    public function getStepData() {
        return [
            [1, 1, 1, [ 1 => [1 => true, 2 => true], 2 => [1 => true, 2 => true] ] ],
            [1, 4, 2, [ 1 => [3 => true, 4 => true, 5 => true], 2 => [3 => true, 4 => true, 5 => true] ] ],
            [4, 1, 3, [ 3 => [1 => true, 2 => true], 4 => [1 => true, 2 => true] , 5 => [1 => true, 2 => true] ] ],
            [4, 4, 4, [ 3 => [3 => true, 4 => true, 5 => true], 4 => [3 => true, 4 => true, 5 => true] , 5 => [3 => true, 4 => true, 5 => true] ] ],
        ];
    }
        
}
