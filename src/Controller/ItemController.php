<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
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
     * @param \App\Services\Cart\CartItem       $cartItem
     *
     * @return Response
     */
    public function index(Request $request, GammeProductServices $gammeProductServices, GammeServices $gammeServices, ShopServices $shopServices, ItemQuantityService $itemQuantityService, CartItem $cartItem): Response
    {

        $society = $this->getUser()->getSocietyID()->getId();
        $id      = $request->get('id');
        $product = $gammeProductServices->getProductInfo($id);


        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'product'         => $product,
            'gamme'           => $gammeServices->getGammeID($product->getGamme()->getId()),
            'itemPrice'       => $shopServices->getPriceItemIDSociety($id, $society),
            'quantity'        => $itemQuantityService->quantityItemSociety($id, $society),
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity']
        ]);

    }
}
