<?php

namespace App\Repository;

use App\Entity\ItemQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemQuantity[]    findAll()
 * @method ItemQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemQuantity::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPriceBySociety($item, $society)
    {
             return $this->createQueryBuilder('i')
            ->andWhere('i.IdSociety = :val')
            ->setParameter('val', $society)
            ->andWhere('i.idItem  = :item')
            ->setParameter('item', $item)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return ItemQuantity[] Returns an array of ItemQuantity objects
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
    public function findOneBySomeField($value): ?ItemQuantity
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
