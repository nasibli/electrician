<?php

namespace Tests\AppBundle\Validator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Validator\ElectricianValidator;

class ElectricianValidatorTest extends WebTestCase {

    private $electricianValidator;
    
    public function setUp() {
        $this->electricianValidator = static::createClient()->getContainer()->get(ElectricianValidator::class);
    }
    
    public function testValidate() {
        // правильные значения
        $rowIndex = 5;
        $colIndex = 3;
        $valid = $this->electricianValidator->validate($rowIndex, $colIndex);
        $this->assertTrue($valid);
        $this->assertCount(0, $this->electricianValidator->getErrors());
        
        // неправильные значения
        $rowIndex = 'ф';
        $colIndex = 6;
        $valid = $this->electricianValidator->validate($rowIndex, $colIndex);
        $this->assertFalse($valid);
        $errors = $this->electricianValidator->getErrors();
        $this->assertArrayHasKey('rowIndex', $errors);
        $this->assertArrayHasKey('colIndex', $errors);
    }
    
}
