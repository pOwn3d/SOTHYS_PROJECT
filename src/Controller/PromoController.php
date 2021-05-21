<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
use App\Services\PromoServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromoController extends AbstractController
{
    /**
     * @Route("/add-cart-promo/{id}", name="app_cart_promo")
     * @param Request $request
     * @param \App\Services\PromoServices $promoServices
     * @return void
     */
    public function addToCartPromo(Request $request, PromoServices $promoServices)
    {
        $id    = $request->get('id');
        $promoServices->addtoCartPromo($id);
    }

    /**
     * @Route("/panier-promo", name="app_promo")
     */
    public function index(ShopServices $shopServices, CartItem $cartItem): Response
    {
        $society = $this->getUser()->getSocietyID();
        $orders  = $shopServices->getOrderDraft($society);

        return $this->render('shop/promo.html.twig', [
            'controller_name' => 'ShopController',
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity']
        ]);
    }
}
