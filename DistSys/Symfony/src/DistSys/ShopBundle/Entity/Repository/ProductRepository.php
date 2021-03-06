<?php

namespace DistSys\ShopBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository {

  /**
   * Get productsByAttributes
   * 
   * @param int $attrId
   * @param array $param
   * @return Product
   */
  public function getProductsByAttribute($attrId, $param = NULL) {
    if ($param) {
      array_key_exists('limit', $param) ? $limit = (int) $param['limit'] : $limit = NULL;
      array_key_exists('offset', $param) ? $offset = (int) $param['offset'] : $offset = NULL;
      array_key_exists('order', $param) ? $order = (string) $param['order'] : $order = NULL;
    }

    $qb = $this->createQueryBuilder('p');
    $qb->select('p')
      ->join('p.attributes', 'a')
      ->where('a.id = :attrId')
      ->setParameter('attrId', $attrId);


    if (isset($offset)) {
      $qb->setFirstResult($offset);
    }
    if (isset($limit)) {
      $qb->setMaxResults($limit);
    }
    if (isset($order)) {
      $qb->orderBy('p.name', $order);
    }

    return $qb->getQuery()->getResult();
  }

}
