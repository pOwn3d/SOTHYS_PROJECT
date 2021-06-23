<?php

namespace App\Repository;

use App\Entity\GammeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GammeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method GammeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method GammeProduct[]    findAll()
 * @method GammeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GammeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GammeProduct::class);
    }

    /**
     * @return GammeProduct[] Returns an array of GammeProduct objects
     */
    public function findProductGamme($societyId)
    {
        $query = $this->createQueryBuilder('g')
            ->select('g, i')
            ->join('g.items', 'i', Join::WITH, 'i.gamme = g.id AND i.idPresentation IN (\'Cabine\', \'Vente\')')
            ->join('i.itemPrices', 'p', Join::WITH, 'p.idItem = i.id AND p.idSociety = :societyId')
            ->setParameter('societyId', $societyId)
            ->orderBy('g.id', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return GammeProduct[] Returns an array of GammeProduct objects
     */
    public function findPLVGamme($societyId)
    {
        $query = $this->createQueryBuilder('g')
            ->select('g')
            ->join('g.items', 'i', Join::WITH, 'i.gamme = g.id AND i.idPresentation NOT IN (\'Cabine\', \'Vente\')')
            ->join('i.itemPrices', 'p', Join::WITH, 'p.idItem = i.id AND p.idSociety = :societyId')
            ->setParameter('societyId', $societyId)
            ->orderBy('g.id', 'ASC')
            ->getQuery();

        return $query->getResult();
    }

}
