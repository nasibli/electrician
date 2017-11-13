<?php

namespace CommonBundle\Validator;

use Symfony\Component\Validator\Validator\RecursiveValidator;

class AbstractValidator {

    protected $errors = [];  

    protected $validator;

    public function __construct(RecursiveValidator $validator) {
        $this->validator = $validator;
    }
  
    public function getErrors() {
        return $this->errors;
    }
  
}