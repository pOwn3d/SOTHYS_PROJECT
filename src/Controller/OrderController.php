<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\PromotionItemRepository;
use App\Services\Cart\CartItem;
use App\Services\OrderDraftServices;
use App\Services\OrderServices;
use App\Services\PromoServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/{_locale}/mes-commandes", name="app_order", requirements={
    * "_locale"="%app.locales%"
    * })
     * @param OrderRepository $orderRepository
     * @param CartItem        $cartItem
     * @param OrderServices   $orderServices
     *
     * @return Response
     */
    public function index(OrderRepository $orderRepository, CartItem $cartItem, OrderServices $orderServices): Response
    {
        $society = $this->getUser()->getSocietyID()->getId();
        $orders  = null;
        $orders  = $orderRepository->findOrderCustomer($society);

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity']
        ]);
    }

    /**
     * @Route("/add-to-cart/{item}/{qty}/{promo}", name="app_add_to_cart_id" , defaults={"promo" = null})
     * @param Request $request
     * @param ShopServices $shopServices
     * @param OrderDraftServices $orderDraftServices
     * @param CartItem $cartItem
     * @param \App\Repository\PromotionItemRepository $promotionItemRepository
     * @return JsonResponse
     * @throws \Exception
     */
    public function addToCart(Request $request, ShopServices $shopServices, OrderDraftServices $orderDraftServices, CartItem $cartItem, PromotionItemRepository$promotionItemRepository): JsonResponse
    {

        $itemId = $request->get('item');
        $promo    = $request->get('promo');
        if ($promo == 'undefined'){
            $promo = 0;
        }

        $society = $this->getUser()->getSocietyID();
        $cartUpdate = $shopServices->addToCart($society, $itemId, $request->get('qty'), $promo);
        $orders = $orderDraftServices->getOrderDraftID($society->getId(), $itemId);
        $sum    = $orderDraftServices->getSumOrderDraft($society->getId(), $promo);


        // Récupérer la promo pour vérifier si le solde de promo est atteind

        $data = [
            'total'            => $sum[0]['price'],
            'quantity'         => $orders->getQuantity(),
            'price'            => $orders->getPrice(),
            'quantityBundling' => $orders->getQuantityBundling(),
            'id'               => $orders->getId(),
            'cartItem'         => $cartItem->getItemCart($society)['0']['quantity'],
            'cartUpdate' => $cartUpdate
        ];

        return new JsonResponse(json_encode($data));
    }

}
