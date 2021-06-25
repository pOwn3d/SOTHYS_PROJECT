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

        $x =  $this->createQueryBuilder('i')
            ->leftJoin('i.gamme', 'gamme')
            ->join('i.itemPrices', 'p', Join::WITH, 'p.idItem = i.id AND p.idSociety = :societyId')
            ->andWhere('gamme.id = :val')
            ->setParameter('societyId', $societyId)
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()

            ;
        dd($x);
//            ->getResult();
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

    public function findProductsByGenericName($value, $societyId, $page = 1, $genericName, $type): array
    {

        $typeCriteria = ' IN (\'Vente\', \'Cabine\')';
        if($type === 'plv') {
            $typeCriteria = ' NOT IN (\'Vente\', \'Cabine\')';
        }

        $limit = 12;
        $sql = 'select
            *,
            item.id as itemId
            from item
            INNER JOIN generic_name on item.generic_name_id = generic_name.id
            INNER JOIN item_price on item_price.id_item_id = item.id AND item_price.id_society_id = ' . $societyId . ' AND item.gamme_id = ' . $value . '
            AND id_presentation ' . $typeCriteria . '
            GROUP BY generic_name_id
            LIMIT ' . $limit .'
            OFFSET '.($page - 1) * $limit;

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAllAssociative();

    }

    public function getPaginationByGenericnameRepo($value, $societyId, $page = 1, $type): \stdClass
    {
        $pagination = new \stdClass();
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select COUNT(*) as total from item INNER JOIN generic_name on item.generic_name_id = generic_name.id INNER JOIN item_price on item_price.id_item_id = item.id AND item_price.id_society_id =  ' . $societyId . ' AND item.gamme_id = ' . $value . '  where item.id AND id_presentation = "' . $type . '" in ( select min(id) from item group by generic_name_id ) and id_presentation = "' . $type . '"';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $itemCount = $stmt->fetch();
        $pagination->pageCount = (int)ceil($itemCount['total'] / 12);
        $pagination->itemCount = $itemCount['total'];
        $pagination->range = range(1, $pagination->pageCount);
        $pagination->currentPage = $page;

        return $pagination;
    }

    public function findAllPlv($societyId)
    {
        return $this->createQueryBuilder('i')
            ->join('i.itemPrices', 'ip', Join::WITH, 'i.id = ip.idItem AND ip.idSociety = :societyId')
            ->join('i.gamme', 'g')
            ->where('i.idPresentation = \'PLV\'')
            ->setParameter('societyId', $societyId)
            ->getQuery()
            ->getResult();
    }

}
