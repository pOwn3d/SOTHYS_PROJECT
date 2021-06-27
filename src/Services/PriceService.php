<?php

namespace App\Services;

use App\Repository\ItemRepository;

class PriceService
{
    /**
     * @var \App\Repository\ItemRepository
     */
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function getAllPrices($societyId, $locale)
    {
        $items = $this->itemRepository->findAllPrice($societyId);

        $prices = [];
        foreach($items as $item) {
            $gammeId = $item->getGamme()->getLabel($locale);
            $genericName = $item->getGenericName()->getName($locale);
            if(!empty($prices[$gammeId][$genericName])) {
                $prices[$gammeId][$genericName][] = $item;
            } else {
                $prices[$gammeId][$genericName] = [$item];
            }
        }

        return $prices;
    }

    public function getPlvPrices($societyId, $locale)
    {
        $items = $this->itemRepository->findAllPlv($societyId);

        $prices = [];
        foreach($items as $item) {
            $gammeId = $item->getGamme()->getLabel($locale);
            if(!empty($prices[$gammeId])) {
                $prices[$gammeId][] = $item;
            } else {
                $prices[$gammeId] = [$item];
            }
        }

        return $prices;
    }
}
