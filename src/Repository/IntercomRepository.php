<?php

namespace App\Repository;

use App\Entity\Intercom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intercom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intercom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intercom[]    findAll()
 * @method Intercom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntercomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intercom::class);
    }

    // /**
    //  * @return Intercom[] Returns an array of Intercom objects
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
    public function findOneBySomeField($value): ?Intercom
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
