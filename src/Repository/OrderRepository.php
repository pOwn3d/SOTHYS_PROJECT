<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */

    public function findOrderCustomer($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.SocietyID = :val')
            ->setParameter('val', $value)
            ->orderBy('o.dateOrder', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOrderCustomerExport()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.idOrderX3 IS NULL')
            ->orderBy('o.dateOrder', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOrderAndLinesCustomerExport()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.idOrderX3 IS NULL')
            ->orderBy('o.dateOrder', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPortfolioOrders(int $societyId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.SocietyID = :societyId')
            ->andWhere('o.idStatut IN (:ids)')
            ->setParameter('societyId', $societyId)
            // TODO : get proper status id
            ->setParameter('ids', [10, 11])
            ->orderBy('o.dateOrder', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
