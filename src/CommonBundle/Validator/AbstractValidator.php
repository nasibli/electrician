<?php

namespace CommonBundle\Validator;
use Symfony\Component\Validator\Validator\RecursiveValidator as SymfonyValidator;

class AbstractValidator {
  /**
   * @var SymfonyValidator
   */
  protected $validator;

  public function __construct(SymfonyValidator $validator) {
    $this->validator = $validator;
  }

  /**
   * @param array $errors
   *
   * @return array
   */
  protected function getErrors($errors) {
    $res = [];
    if (count($errors) > 0) {
      foreach ($errors as $error) {
        $res[$error->getPropertyPath()] = $error->getMessage();
      }
    }
    return $res;
  }

}