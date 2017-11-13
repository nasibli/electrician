<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use AppBundle\Manager\ElectricianManager;

class AbstractTestCase extends WebTestCase {

    /** @var Client */
    protected $client = null;
    
    protected function setUp () {
        parent::setUp();
        
        $this->client = static::createClient();
    }
    
    protected function loadFixtures() {
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $loader = new Loader;
        $loader->addFixture(new \AppBundle\DataFixtures\ORM\ResultsFixture($entityManager));
        $executor = new ORMExecutor($entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures(), false);
    }
    
    public function tearDown() {
        $this->client->getContainer()->tearDown();
        $this->client = null;
        
        parent::tearDown();
    }
    
    public function getElectricianManagerMock($randomResult, $randomIndex = null) {
        $methods = ['isRandom'];
        if ($randomResult) {
            $methods[] = 'getRandomCell';
        }
        $mock = $this->getMockBuilder(ElectricianManager::class)
                    ->setConstructorArgs([ $this->client->getContainer()->get('session') ])
                    ->setMethods($methods)
                    ->getMock();
        
        $mock->expects($this->any())
            ->method('isRandom')
            ->willReturn($randomResult);
        
        if ($randomResult) {
            $mock->expects($this->any())
                ->method('getRandomCell')
                ->willReturn(['rowIndex' => $randomIndex, 'colIndex' => $randomIndex]);
        }
                
        return $mock;        
    }
    
}
