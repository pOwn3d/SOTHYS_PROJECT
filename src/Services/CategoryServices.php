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

    public function getGamme(string $prefix = 'SV')
    {
        return $this->gammeProductRepository->findByStartingPrefix($prefix);
    }

}
