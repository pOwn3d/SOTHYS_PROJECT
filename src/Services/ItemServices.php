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

    public function getItems($items)
    {

        foreach ($items as $item) {


            $x =
                $this->itemRepository->find($item->getIdItem()->getId());


            dd($x);
        }

    }

    function getItemByX3Id($x3Id) {
        return $this->itemRepository->findOneBy([
            'itemID' => $x3Id
        ]);
    }
}
