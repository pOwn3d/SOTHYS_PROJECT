<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
use App\Services\GammeProductServices;
use App\Services\GammeServices;
use App\Services\ItemQuantityService;
use App\Services\ItemServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/{_locale}/produit/{id}", name="app_item_id", requirements={
     * "_locale"="%app.locales%"
     * })
     * @param Request $request
     * @param GammeProductServices $gammeProductServices
     * @param GammeServices $gammeServices
     * @param ShopServices $shopServices
     * @param ItemQuantityService $itemQuantityService
     * @param CartItem $cartItem
     * @param \App\Services\ItemServices $itemServices
     * @return Response
     */
    public function index(Request $request, GammeProductServices $gammeProductServices, GammeServices $gammeServices, ShopServices $shopServices, ItemQuantityService $itemQuantityService, CartItem $cartItem, ItemServices $itemServices): Response
    {
        $societyId = $this->getUser()->getSocietyID()->getId();
        $id      = $request->get('id');
        $product = $gammeProductServices->getProductInfo($id);
        $relatedProducts =  $itemServices->relatedProduct($product->getGenericName()->getId());

        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'product'         => $product,
            'gamme'           => $gammeServices->getGammeID($product->getGamme()->getId()),
            'itemPrice'       => $shopServices->getPriceItemIDSociety($id, $societyId),
            'quantity'        => $itemQuantityService->quantityItemSociety($id, $societyId),
            'cartItem'        => $cartItem->getItemCart($societyId),
            'relatedProducts' => $relatedProducts
        ]);
    }
}
