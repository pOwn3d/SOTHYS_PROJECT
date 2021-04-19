<?php


namespace App\Services;


use App\Entity\Item;
use App\Repository\ItemRepository;

class GammeProductServices
{

    /**
     * @var ItemRepository
     */
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function getProductInfo($id): ?Item
    {
        return $this->itemRepository->findOneBy([ 'id' => $id ]);
    }

    public function findByGammeId($id): array
    {
        return $this->itemRepository->findByGammeId($id);
    }

}
