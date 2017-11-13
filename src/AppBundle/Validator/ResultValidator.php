<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraints;

use CommonBundle\Validator\AbstractValidator;

class ResultValidator extends AbstractValidator {
       
    public function validate($userName, $stepCount) {
        $userConstraint = new Constraints\NotBlank();
        $errors = $this->validator->validate($userName, $userConstraint);
        if (count($errors) > 0) {
            $this->errors['userName'] = $errors[0]->getMessage();
        }
        
        $stepConstraint = new Constraints\GreaterThanOrEqual(4);
        $errors = $this->validator->validate($stepCount, $stepConstraint);
        if (count($errors) > 0) {
            $this->errors['stepCount'] = $errors[0]->getMessage();
        }
        
        return ! $this->errors;
    }
   
}
