<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\ResultEntity;

class ResultsFixture extends Fixture {
    
    public function load (ObjectManager $objectManager) {
        
        foreach ($this->getData() as [$userName, $stepCount]) {
            $result = new ResultEntity();
            $result->userName = $userName;
            $result->stepCount = $stepCount;
            $objectManager->persist($result);
        }
        
        $objectManager->flush();
    }
    
    private function getData() {
        return [
            ['Дима',   15],
            ['Маша',   20],
            ['Сергей', 30],
            ['Даниил', 30],
            ['Саша',   9],
            ['Даша',   8],
            ['Игорь',  7],
            ['Петр',   6],
            ['Матвей', 5],
            ['Алекс',  4],
        ];
    }
    
}
