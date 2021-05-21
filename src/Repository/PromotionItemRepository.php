<?php

namespace App\Repository;

use App\Entity\PromotionItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromotionItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionItem[]    findAll()
 * @method PromotionItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionItem::class);
    }

    // /**
    //  * @return PromotionItem[] Returns an array of PromotionItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionItem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
