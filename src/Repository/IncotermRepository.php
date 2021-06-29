<?php

namespace App\Repository;

use App\Entity\Incoterm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Incoterm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incoterm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incoterm[]    findAll()
 * @method Incoterm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncotermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incoterm::class);
    }

    // /**
    //  * @return Incoterm[] Returns an array of Incoterm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Incoterm
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
