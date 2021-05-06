<?php

namespace App\Repository;

use App\Entity\CustomerIncoterm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerIncoterm|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerIncoterm|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerIncoterm[]    findAll()
 * @method CustomerIncoterm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerIncotermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerIncoterm::class);
    }

    // /**
    //  * @return CustomerIncoterm[] Returns an array of CustomerIncoterm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomerIncoterm
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
