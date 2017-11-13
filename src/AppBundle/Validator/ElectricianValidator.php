<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraints;

use CommonBundle\Validator\AbstractValidator;

class ElectricianValidator extends AbstractValidator {
    
    public function validate($rowIndex, $colIndex) {
        $indexConstraint = new Constraints\Range(['min' => 1, 'max' => 5]);
  
        $errors = $this->validator->validate($rowIndex, $indexConstraint);
        if (count($errors) > 0) {
            $this->errors['rowIndex'] = $errors[0]->getMessage();
        }
        
        $errors = $this->validator->validate($colIndex, $indexConstraint);
        if (count($errors) > 0) {
            $this->errors['colIndex'] = $errors[0]->getMessage();
        }
        
        return ! $this->errors;
    }
        
}
