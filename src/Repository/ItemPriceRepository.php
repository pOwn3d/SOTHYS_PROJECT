<?php

namespace App\Repository;

use App\Entity\ItemPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemPrice[]    findAll()
 * @method ItemPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemPrice::class);
    }


    public function getPriceBySociety($item, $society)
    {
   
        $query = $this->createQueryBuilder('i')
            ->andWhere('i.idItem = :val')
            ->setParameter('val', $item)
            ->andWhere('i.idSociety  = :society')
            ->setParameter('society', $society)
            ->setMaxResults(1)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $query['0'];
    }

    // /**
    //  * @return ItemPrice[] Returns an array of ItemPrice objects
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
    public function findOneBySomeField($value): ?ItemPrice
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
