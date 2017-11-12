<?php

namespace AppBundle\Manager;

use AppBundle\Dao\ResultDAO;

class ResultManager {
    
    const BEST_COUNT = 10;
    
    private $resultDAO;
    
    public function __construct(ResultDAO $resultDAO) {
        $this->resultDAO = $resultDAO;
    }
    
    public function add($userName, $stepCount) {
        $this->resultDAO->add($userName, $stepCount);
    }
    
    public function getBest() {
        return $this->resultDAO->getBest(self::BEST_COUNT);
    }
    
}
