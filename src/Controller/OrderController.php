<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\Cart\CartItem;
use App\Services\OrderDraftServices;
use App\Services\OrderServices;
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
     * @param CartItem $cartItem
     * @return Response
     */
    public function index(OrderRepository $orderRepository, CartItem $cartItem): Response
    {
        $society = $this->getUser()->getSocietyID();
        $orders  = $orderRepository->findOrderCustomer($society);
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($society->getId())
        ]);
    }

    /**
     * @Route("/add-to-cart/{item}/{qty}/{promo}", name="app_add_to_cart_id" , defaults={"promo" = null})
     * @param Request $request
     * @param ShopServices $shopServices
     * @param OrderDraftServices $orderDraftServices
     * @param CartItem $cartItem
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addToCart(Request $request, ShopServices $shopServices, OrderDraftServices $orderDraftServices, CartItem $cartItem): Response
    {

        // TODO :: Récupérer les info de FreeRestockingRules pour mettre à jour le panier
        $itemId = $request->get('item');
        $promo = $request->get('promo');
        if ($promo == 'undefined') {
            $promo = 0;
        }

        $society = $this->getUser()->getSocietyID();
        $cartUpdate = $shopServices->addToCart($society, $itemId, $request->get('qty'), $promo);
        $orders = $orderDraftServices->getAllOrderDraftID($society->getId(), $itemId);
        $sum = $orderDraftServices->getSumOrderDraft($society->getId(), $promo);


        if ($promo != 0) {
            $orders = $orderDraftServices->getAllOrderDraftPromoID($society->getId());
            $x = $this->renderView('shop/promo_ajax.html.twig', [
                'orders' => $orders,
            ]);
            return new Response(json_encode($x));
        }

        if ($cartUpdate == true) {
            return new JsonResponse(json_encode($cartUpdate));
        } else {
            foreach ($orders as $order) {
                $data = [
                    'total' => $sum[0]['price'],
                    'quantity' => $order->getQuantity(),
                    'price' => $order->getPrice(),
                    'quantityBundling' => $order->getQuantityBundling(),
                    'id' => $order->getId(),
                    'cartItem' => $cartItem->getItemCart($society->getId()),
                    'cartUpdate' => $cartUpdate
                ];
            }
        }
        return new JsonResponse(json_encode($data));

    }


    /**
     * @Route("/add-to-cart-restocking/{item}/{qty}/{ajax}", name="app_add_to_cart_restocking" )
     * @param Request $request
     * @param ShopServices $shopServices
     * @param OrderDraftServices $orderDraftServices
     * @param CartItem $cartItem
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addToCartRestocking(Request $request, ShopServices $shopServices, OrderDraftServices $orderDraftServices, CartItem $cartItem): Response
    {

        $itemId = $request->get('item');
        $society = $this->getUser()->getSocietyID();
        $cartUpdate = $shopServices->addToCartRestocking($society, $itemId, $request->get('qty'));
        $orders = $orderDraftServices->getAllOrderDraftID($society->getId(), $itemId);


        if ($request->get('ajax') == 0) {
            foreach ($orders as $order) {
                $data = [
                    'quantity' => $order->getQuantity(),
                    'price' => $order->getPrice(),
                    'quantityBundling' => $order->getQuantityBundling(),
                    'id' => $order->getId(),
                    'cartItem' => $cartItem->getItemCart($society->getId()),
                    'error' => $cartUpdate
                ];

                return new Response(json_encode($data));
            }

            if ($cartUpdate != "") {
                $this->addFlash('erreur', $cartUpdate);
            }
        }

        return $this->redirectToRoute('app_shop');
    }


}
