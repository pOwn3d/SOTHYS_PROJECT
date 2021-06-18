<?php

namespace App\Repository;

use App\Entity\OrderDraft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderDraft|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDraft|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDraft[]    findAll()
 * @method OrderDraft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDraftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDraft::class);
    }


    public function findOrderDraftSociety($society)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo IS  NULL')
            ->orWhere('o.promo = false')
            ->setParameter('val', $society)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOrderDraft($society, $promo)
    {

        return $this->createQueryBuilder('o')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo = :promo')
            ->setParameter('val', $society)
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult();
    }

    public function findOrderDraftSocietyPromo($society)
    {

        return $this->createQueryBuilder('o')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo = true')
            ->setParameter('val', $society)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findSumOrderDraftSociety($society, $promo)
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.priceOrder) as price')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo = :promo')
            ->setParameter('val', $society)
            ->setParameter('promo', $promo)
            ->getQuery()
            ->getResult();
    }

    public function findSumItemOrderDraftSociety($society)
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.quantity) as quantity')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo IS  NULL')
            ->orWhere('o.promo = false')
            ->setParameter('val', $society)
            ->getQuery()
            ->getResult();
    }

    public function findLineOrderDraftSociety($society)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.idSociety = :val')
            ->andWhere('o.promo IS  NULL')
            ->orWhere('o.promo = false')
            ->setParameter('val', $society)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return OrderDraft[] Returns an array of OrderDraft objects
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
    public function findOneBySomeField($value): ?OrderDraft
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
