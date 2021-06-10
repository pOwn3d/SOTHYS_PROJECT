<?php


namespace App\Services;


use App\Repository\ItemRepository;

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

    function getItemsByTerm(string $text, string $locale = 'en-US') {

        $qb = $this->itemRepository
            ->createQueryBuilder('i')
            ->join('i.gamme', 'g');

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

        $results = $qb->setParameter('text', "%$text%")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $results;
    }
}
