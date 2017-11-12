<?php

namespace AppBundle\Dao;

use AppBundle\Entity\ResultEntity;
use CommonBundle\Dao\AbstractDAO;


class ResultDAO extends AbstractDAO {
    
    public function add($userName, $stepCount) {
        $result = new ResultEntity;
        $result->userName  = $userName;
        $result->stepCount = $stepCount;
        $this->save($result);
    }
    
    public function getBest($count) {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('r')
            ->from(ResultEntity::class, 'r')
            ->orderBy('r.stepCount', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($count);
        return $qb->getQuery()->getArrayResult();
    }
    
}
