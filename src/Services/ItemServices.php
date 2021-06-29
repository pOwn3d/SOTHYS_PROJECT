<?php


namespace App\Services;


use App\Repository\ItemRepository;
use Doctrine\ORM\Query\Expr\Join;

class ItemServices
{

    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    function getItemByX3Id($x3Id) {
        return $this->itemRepository->findOneBy([
            'itemID' => $x3Id
        ]);
    }

    function getItemsByTerm(int $societyId, string $text, string $locale = 'en-US') {

        $qb = $this->itemRepository
            ->createQueryBuilder('i')
            ->join('i.gamme', 'g')
            ->innerJoin('i.itemPrices', 'p', Join::WITH, 'i.id = p.idItem AND p.idSociety = :societyId');

        if($locale === 'fr-FR') {
            $qb->where('i.labelFR LIKE :text');
        } else {
            $qb->where('i.labelEN LIKE :text');
        }

        $qb->orWhere('i.itemID LIKE :text');

        if($locale === 'fr-FR') {
            $qb->orWhere('g.labelFR LIKE :text');
        } else {
            $qb->orWhere('g.labelEN LIKE :text');
        }

        $results = $qb
            ->setParameter('text', "%$text%")
            ->setParameter('societyId', $societyId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $results;
    }

    function getItemsByFreeRestocking(int $societyId, string $text, string $locale = 'en-US', $freeRule) {

//
//dd('i.id = p.idItem AND p.idSociety = :societyId AND i.idPresentation  IN ' .$freeRule);

        $qb = $this->itemRepository
            ->createQueryBuilder('i')
            ->join('i.gamme', 'g')
            ->innerJoin('i.itemPrices', 'p', Join::WITH, 'i.id = p.idItem AND p.idSociety = :societyId AND i.idPresentation  IN (' .$freeRule .')' );

        if($locale === 'fr-FR') {
            $qb->where('i.labelFR LIKE :text');
        } else {
            $qb->where('i.labelEN LIKE :text');
        }

        $qb->orWhere('i.itemID LIKE :text');

        if($locale === 'fr-FR') {
            $qb->orWhere('g.labelFR LIKE :text');
        } else {
            $qb->orWhere('g.labelEN LIKE :text');
        }

        $results = $qb
            ->setParameter('text', "%$text%")
            ->setParameter('societyId', $societyId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function relatedProduct($id){
      return  $this->itemRepository->findBy(['genericName' => $id]);
    }
}
