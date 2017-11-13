<?php

namespace AppBundle\Manager;

use Symfony\Component\HttpFoundation\Session\Session;

class ElectricianManager {
    
    const SIZE = 5;
    
    const PATTERN_NUMBER = 13;
    const RANDOM_MIN = 1;
    const RANDOM_MAX = 25;
        
    private $sessionService;
    private $board;
        
    public function __construct(Session $sessionService) {
        $this->sessionService = $sessionService;
        if ($this->sessionService->get('board')) {
            $this->board = $this->sessionService->get('board');
        }
    }
    
    public function init() {
        $this->board = $this->createBoard();
        $this->sessionService->set('board', $this->board);
        $this->sessionService->set('stepCount', 0);
    }
    
    public function step(int $rowIndex, int $colIndex) {
        $changedCells = $this->getNearCells($rowIndex, $colIndex);
        
        $changedCells[$rowIndex] = $changedCells[$rowIndex] ?? [];
        $changedCells[$rowIndex][$colIndex] = true;
        
        if ($this->isRandom()) {
            $cell = $this->getRandomCell();
            $changedCells[$cell['rowIndex']] = $changedCells[$cell['rowIndex']] ?? [];
            $changedCells[$cell['rowIndex']][$cell['colIndex']] = ! $this->board[$cell['rowIndex']][$cell['colIndex']];
        }
        
        $this->updateBoard($changedCells);
        $this->sessionService->set('stepCount', $this->sessionService->get('stepCount') + 1);
        return $changedCells;
    }
    
    public function getStepCount() {
        return $this->sessionService->get('stepCount');
    }

    public function getBoard() {
        return $this->board;
    }

    private function getNearCells (int $rowIndex, int $colIndex) {
        $cells = [];
        
        //левая сторона
        if ($colIndex > 1) {
            //левая ячейка
            $cells[$rowIndex] = [ $colIndex-1 => ! $this->board[$rowIndex][$colIndex-1] ];
            
            //диагональ вверх
            if ($rowIndex > 1) {
                $cells[$rowIndex-1] = [$colIndex-1 => ! $this->board[$rowIndex-1][$colIndex-1] ];
            }
            
            //диагональ вниз
            if ($rowIndex < self::SIZE) {
                $cells[$rowIndex+1] = [$colIndex-1 => ! $this->board[$rowIndex+1][$colIndex-1]] ;
            }
        }
        
        //правая сторона
        if ($colIndex < self::SIZE) {
            $cells[$rowIndex] = $cells[$rowIndex] ?? [];         
            
            //правая ячейка
            $cells[$rowIndex][$colIndex+1] = ! $this->board[$rowIndex][$colIndex+1];
            
            //диагональ вверх
            if ($rowIndex > 1) {
                $cells[$rowIndex-1] = $cells[$rowIndex-1] ?? [];
                $cells[$rowIndex-1][$colIndex+1] = ! $this->board[$rowIndex-1][$colIndex+1];
            }
            
            //диагональ вниз
            if ($rowIndex < self::SIZE) {
                $cells[$rowIndex+1] = $cells[$rowIndex+1] ?? [];
                $cells[$rowIndex+1][$colIndex+1] = ! $this->board[$rowIndex+1][$colIndex+1];
            }
        }
        
        //верхняя ячейка
        if ($rowIndex > 1) { 
            $cells[$rowIndex-1] = $cells[$rowIndex-1] ?? [];
            $cells[$rowIndex-1][$colIndex] = ! $this->board[$rowIndex-1][$colIndex];
        }
        
        //нижняя ячейка
        if ($rowIndex < self::SIZE) {
            $cells[$rowIndex + 1] = $cells[$rowIndex + 1] ?? [];
            $cells[$rowIndex + 1][$colIndex] = ! $this->board[$rowIndex + 1][$colIndex];
        }
        
        return $cells;
    }
    
    public function isCompleted() {
        foreach ($this->board as $cols) {
            foreach ($cols as $checked) {
                if ( ! $checked) {
                    return false;
                }
            }
        }
        return true;
    }
    
    private function createBoard() {
        $board = [];
        for ($rowIndex = 1; $rowIndex <= self::SIZE; $rowIndex++) {
            $cols=[];
            for ($colIndex = 1; $colIndex <= self::SIZE; $colIndex++) {
                $cols[$colIndex] = false;
            }
            $board[$rowIndex] = $cols;
        }
        return $board;
    }
    
    private function updateBoard($cells) {
        foreach ($cells as $rowIndex => $columns) {
            foreach ($columns as $colIndex => $checked) {
                $this->board[$rowIndex][$colIndex] = $checked;
            }
        }
        $this->sessionService->set('board', $this->board);
    }
    
    public function isRandom () {
        return random_int(self::RANDOM_MIN, self::RANDOM_MAX) === self::PATTERN_NUMBER;
    }
           
    public function getRandomCell() {
        return [
            'rowIndex' => random_int(1, self::SIZE),
            'colIndex' => random_int(1, self::SIZE)
        ];
    }
    
    
    
}
