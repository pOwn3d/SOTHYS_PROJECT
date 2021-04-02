<?php

namespace App\Repository;

use App\Entity\GammeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GammeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method GammeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method GammeProduct[]    findAll()
 * @method GammeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GammeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GammeProduct::class);
    }

    // /**
    //  * @return GammeProduct[] Returns an array of GammeProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GammeProduct
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
