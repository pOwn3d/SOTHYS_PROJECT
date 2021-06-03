<?php

namespace App\Repository;

use App\Entity\FreeRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FreeRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method FreeRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method FreeRules[]    findAll()
 * @method FreeRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreeRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FreeRules::class);
    }

    // /**
    //  * @return FreeRules[] Returns an array of FreeRules objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FreeRules
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
