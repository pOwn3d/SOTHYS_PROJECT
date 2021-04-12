<?php


namespace App\Services;


use App\Entity\Item;
use App\Repository\ItemRepository;

class ProductServices
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

}
