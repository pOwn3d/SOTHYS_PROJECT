<?php

namespace App\Repository;

use App\Entity\OrderLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderLine[]    findAll()
 * @method OrderLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLine::class);
    }


    public function findAllByX3($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idOrderX3 = :val')
            ->setParameter('val', $value)
            ->orderBy('s.idOrderX3', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByOrderID($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idOrder = :val')
            ->setParameter('val', $value)
            ->orderBy('s.idOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function sumOrderByX3($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idOrderX3  = :val')
            ->setParameter('val', $value['idOrderX3'])
            ->select('SUM(s.price) as price')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteOrderLine($id){

        return $this->createQueryBuilder('s')
            ->delete('')
            ->where('s.idOrder = :id')
            ->setParameter("id", $id)
            ->getQuery()
            ->execute();

    }


    // /**
    //  * @return OrderLine[] Returns an array of OrderLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderLine
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
