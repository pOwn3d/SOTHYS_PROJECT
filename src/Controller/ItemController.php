<?php

namespace App\Controller;

use App\Services\GammeProductServices;
use App\Services\GammeServices;
use App\Services\ItemQuantityService;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/produit/{id}", name="app_item_id")
     * @param Request                           $request
     * @param GammeProductServices              $gammeProductServices
     * @param GammeServices                     $gammeServices
     * @param ShopServices                      $shopServices
     * @param \App\Services\ItemQuantityService $itemQuantityService
     *
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(Request $request, GammeProductServices $gammeProductServices, GammeServices $gammeServices, ShopServices $shopServices, ItemQuantityService $itemQuantityService): Response
    {
        $society   = $this->getUser()->getSocietyID()->getId();
        $id        = $request->get('id');
        $product   = $gammeProductServices->getProductInfo($id);
        $gamme     = $gammeServices->getGammeID($product->getGamme()->getId());
        $itemPrice = $shopServices->getPriceItemIDSociety($id, $society);
        $quantity  = $itemQuantityService->quantityItemSociety($id, $society);

        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'product'         => $product,
            'gamme'           => $gamme,
            'itemPrice'       => $itemPrice,
            'quantity'        => $quantity
        ]);

    }
}
