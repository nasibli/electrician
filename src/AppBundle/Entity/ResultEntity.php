<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hotel
 *
 * @ORM\Table(name="result")
 * @ORM\Entity
 */
class ResultEntity {
    
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="bigint")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  protected $id;
  
  /**
   * @var string
   *
   * @ORM\Column(name="user_name", type="string", length=100, nullable=false)
   */
  public $userName;
  
  /**
   * @var integer
   *
   * @ORM\Column(name="stepCount", type="integer", nullable=false)
   */
  public $stepCount;
    
}
