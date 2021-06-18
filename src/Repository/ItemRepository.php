<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[] Returns an array of Item objects
     */
    public function findProductsByGammeId($value, $societyId, $page = 1): array
    {
        $limit = 12;

        return $this->createQueryBuilder('i')
            ->leftJoin('i.gamme', 'gamme')
            ->join('i.itemPrices', 'p', Join::WITH, 'p.idItem = i.id AND p.idSociety = :societyId')
            ->andWhere('gamme.id = :val')
            ->setParameter('societyId', $societyId)
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
//            ->addOrderBy('i.idPresentation = "vente"')
            ->getQuery()
            ->getResult();
    }

    public function getPaginationByGammeId($value, $societyId, $page = 1)
    {
        $pagination = new \stdClass();

        $itemCount = $this->createQueryBuilder('i')
            ->leftJoin('i.gamme', 'gamme')
            ->join('i.itemPrices', 'p', Join::WITH, 'p.idItem = i.id AND p.idSociety = :societyId')
            ->andWhere('gamme.id = :val')
            ->setParameter('societyId', $societyId)
            ->setParameter('val', $value)
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $pagination->pageCount = (int) ceil($itemCount / 12);
        $pagination->itemCount = $itemCount;
        $pagination->range = range(1, $pagination->pageCount);
        $pagination->currentPage = $page;

        return $pagination;
    }

    public function findProduct($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //   SELECT * FROM item LEFT JOIN item_price ON item.id = item_price.id_item_id WHERE price is null
    public function removeOldProduct()
    {
        $items = $this->createQueryBuilder('i')
            ->leftJoin('i.itemPrices', 'ip')
            ->where('ip.price is NULL ')
            ->getQuery()
            ->getResult();

        foreach ($items as $item) {
            $this->getEntityManager()->remove($item);
            $this->getEntityManager()->flush();
        }
    }

}
