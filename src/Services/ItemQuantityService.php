<?php


namespace App\Services;


use App\Repository\ItemQuantityRepository;

class ItemQuantityService
{


    private ItemQuantityRepository $itemQuantityRepository;

    public function __construct(ItemQuantityRepository $itemQuantityRepository)
    {
        $this->itemQuantityRepository = $itemQuantityRepository;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function quantityItemSociety($item, $society)
    {
        return $this->itemQuantityRepository->getPriceBySociety($item, $society);
    }

}
