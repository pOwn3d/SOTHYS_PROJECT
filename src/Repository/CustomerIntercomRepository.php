<?php

namespace App\Repository;

use App\Entity\CustomerIntercom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerIntercom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerIntercom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerIntercom[]    findAll()
 * @method CustomerIntercom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerIntercomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerIntercom::class);
    }

    // /**
    //  * @return CustomerIntercom[] Returns an array of CustomerIntercom objects
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
    public function findOneBySomeField($value): ?CustomerIntercom
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
