<?php

namespace App\Repository;

use App\Entity\FreeRestockingRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FreeRestockingRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method FreeRestockingRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method FreeRestockingRules[]    findAll()
 * @method FreeRestockingRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreeRestockingRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FreeRestockingRules::class);
    }

    public function freeRestockingRulesSociety($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.societyId = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return FreeRestockingRules[] Returns an array of FreeRestockingRules objects
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
    public function findOneBySomeField($value): ?FreeRestockingRules
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
