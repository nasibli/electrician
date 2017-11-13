<?php

namespace Tests\AppBundle\Validator;

namespace Tests\AppBundle\Validator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Validator\ResultValidator;

class ResultValidatorTest extends WebTestCase {
    
    private $resultValidator;
            
    public function setUp() {
        $this->resultValidator = static::createClient()->getContainer()->get(ResultValidator::class);
    }
    
    public function testValidate() {
        //правильные данные
        $userName = 'Александр';
        $stepCount = 5;
        $valid = $this->resultValidator->validate($userName, $stepCount);
        $this->assertTrue($valid);
        
        $userName = '';
        $stepCount = 's';
        $valid = $this->resultValidator->validate($userName, $stepCount);
        $this->assertFalse($valid);
        $errors = $this->resultValidator->getErrors();
        $this->assertArrayHasKey('userName', $errors);
        $this->assertArrayHasKey('stepCount', $errors);
    }


}
