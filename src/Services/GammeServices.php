<?php


namespace App\Services;

use App\Entity\GammeProduct;
use App\Repository\GammeProductRepository;

class GammeServices
{

    private GammeProductRepository $gammeProductRepository;

    public function __construct(GammeProductRepository $gammeProductRepository)
    {
        $this->gammeProductRepository = $gammeProductRepository;
    }

    public function getGammeID($id): ?GammeProduct
    {

        return $this->gammeProductRepository->findOneBy([
            'id' => $id,
        ]);

    }
}
