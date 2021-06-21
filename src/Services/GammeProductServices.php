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

    public function findProductsByGammeId($id, $societyId, $page = 1): array
    {
        return $this->itemRepository->findProductsByGammeId($id, $societyId, $page);
    }

    public function getPaginationByGammeId($id, $societyId, $page = 1)
    {
        return $this->itemRepository->getPaginationByGammeId($id, $societyId, $page);
    }

    public function findProductsByGenericName($id, $societyId, $page = 1, $genericName): array
    {
        return $this->itemRepository->findProductsByGenericName($id, $societyId, $page, $genericName);
    }

    public function getPaginationByGenericName($id, $societyId, $page = 1)
    {
        return $this->itemRepository->getPaginationByGenericnameRepo($id, $societyId, $page);
    }

}
