<?php


namespace App\Services;

use App\Repository\GammeProductRepository;

class CategoryServices
{

    /**
     * @var GammeProductRepository
     */
    private GammeProductRepository $gammeProductRepository;

    public function __construct(GammeProductRepository $gammeProductRepository)
    {
        $this->gammeProductRepository = $gammeProductRepository;
    }

    public function getProductGamme($societyId)
    {
        return $this->gammeProductRepository->findProductGamme($societyId);
    }

    public function getPLVGamme($societyId)
    {
        return $this->gammeProductRepository->findPLVGamme($societyId);
    }

}
