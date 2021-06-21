<?php


namespace App\Services;

use App\Repository\GenericNameRepository;

class GenericNameServices
{

    /**
     * @var \App\Repository\GenericNameRepository
     */
    private GenericNameRepository $genericName;

    public function __construct(GenericNameRepository $genericNameRepository)
    {
        $this->genericName = $genericNameRepository;
    }

    public function allGenericName()
    {
        $items = $this->genericName->findAll();

        foreach ($items as $item) {
            $product[] = $item->getId();
        }
        return $product;

    }
}
