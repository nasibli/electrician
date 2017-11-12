<?php

namespace CommonBundle\Dao;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

abstract class AbstractDAO {
  /** @var EntityManager  */
  protected $entityManager;

  /**
   * @param $entityManager
   */
  public function __construct (EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  /**
   * @param            $id
   * @param bool|false $hydrate
   *
   * @return array|object
   */
  public function getById ($id, $hydrate=false) {
    $qb = $this->entityManager->createQueryBuilder()
      ->select('e')
      ->from($this->getEntityName(), 'e')
      ->andWhere('e.id=:id')
      ->setParameter('id', $id);
    return $qb->getQuery()->getOneOrNullResult($hydrate ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
  }

  /**
   * @param array $ids
   * @param bool|false $hydrate
   *
   * @return array
   */
  public function getByIds ($ids, $hydrate=false) {
    $qb = $this->entityManager->createQueryBuilder();
    $qb
      ->select('e')
      ->from($this->getEntityName(), 'e')
      ->andWhere($qb->expr()->in('e.id', ':ids'))
      ->setParameter('ids', $ids);
    return $qb->getQuery()->getResult($hydrate ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
  }

  /**
   * @param            $entity
   * @param bool|false $flush
   */
  public function save ($entity, $flush=true) {
    $this->entityManager->persist($entity);
    if ($flush) {
      $this->entityManager->flush();
    }
  }

  /**
   * @param            $entity
   * @param bool|false $flush
   */
  public function delete ($entity, $flush = false) {
    $this->entityManager->remove($entity);
    if ($flush) {
      $this->entityManager->flush();
    }
  }

  /**
   * @param int $id
   */
  public function deleteById ($id) {
    $dql = 'Delete from ' . $this->getEntityName() . ' e where e.id = :id';
    $query = $this->entityManager->createQuery($dql)->setParameter('id', $id);
    $query->execute();
  }

  public function deleteByIds ($ids) {
    $dql = 'Delete from ' . $this->getEntityName() . ' e where e.id in(:ids)';
    $query = $this->entityManager->createQuery($dql)->setParameter('ids', $ids);
    $query->execute();
  }

  /**
   * @param            $entityName
   * @param            $id
   * @param bool|false $hydrate
   *
   * @return array|object
   */
  public function getRelation ($entityName, $id, $hydrate=false) {
    $qb = $this->entityManager->createQueryBuilder()
      ->select('e')
      ->from($entityName, 'e')
      ->where('e.id = :id')
      ->setParameter('id', $id);
    return $qb->getQuery()->getOneOrNullResult($hydrate ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
  }

  /**
   * Flush
   */
  public function flush () {
    $this->entityManager->flush();
  }

  /**
   * @param bool|false $hydrate
   *
   * @return array
   */
  public function getAll ($hydrate = false) {
    $qb = $this->entityManager->createQueryBuilder();
    $qb
      ->select('e')
      ->from($this->getEntityName(), 'e')
      ->addOrderBy('e.id', 'ASC');
    return $qb->getQuery()->getResult($hydrate ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
  }

  /**
   * Begin transaction
   */
  public function beginTransaction () {
    $this->entityManager->beginTransaction();
  }

  /**
   * Commit transaction
   */
  public function commitTransaction() {
    $this->entityManager->commit();
  }

  /**
   * Rollback transaction
   */
  public function rollbackTransaction() {
    $this->entityManager->rollback();
  }
}