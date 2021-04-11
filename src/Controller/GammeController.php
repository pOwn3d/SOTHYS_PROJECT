<?php

namespace App\Controller;

use App\Repository\GammeProductRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GammeController extends AbstractController
{
    /**
     * @Route("/gamme/{gamme_id}", name="app_gamme")
     * @return Response
     */
    public function index(GammeProductRepository $gammeProductRepository, ItemRepository $itemRepository, Request $request): Response
    {

        $id= $request->get('gamme_id');

        $products = $itemRepository->findByGammeId($id);
        $gamme = $gammeProductRepository->findOneBy([
            'id' => $id,
        ]);

        return $this->render('gamme/index.html.twig', [
            'controller_name' => 'GammeController',
            'gamme' => $gamme,
            'products' => $products
        ]);
    }
}
