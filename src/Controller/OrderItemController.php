<?php

namespace App\Controller;

use App\Repository\OrderLineRepository;
use App\Services\Cart\CartItem;
use App\Services\OrderServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{
    /**
     * @Route("/{_locale}/commande/{id}", name="app_order_item_id")
     * @param Request             $request
     * @param OrderLineRepository $orderLineRepository
     * @param OrderServices       $orderServices
     * @param CartItem            $cartItem
     *
     * @return Response
     */
    public function index(Request $request, OrderLineRepository $orderLineRepository, OrderServices $orderServices, CartItem $cartItem): Response
    {
        return $this->render('order/order.item.html.twig', [
            'controller_name' => 'OrderItemController',
            'orders'          => $orderLineRepository->findAllByX3($request->get('id')),
            'orderX3'         => $orderServices->getOrderByX3($request->get('id')),
            'orderSum'        => $orderServices->getSumOrderLine($request->get('id')),
            'cartItem'        => $cartItem->getItemCart($this->getUser()->getSocietyID())['0']['quantity']
        ]);
    }

    /**
     * @Route("/{_locale}/commande/draft/{id}", name="app_order_item_id_draft")
     * @param Request             $request
     * @param OrderServices       $orderServices
     * @param OrderLineRepository $orderLineRepository
     * @param CartItem            $cartItem
     *
     * @return Response
     */
    public function commandDraft(Request $request, OrderServices $orderServices, OrderLineRepository $orderLineRepository, CartItem $cartItem): Response
    {
        return $this->render('order/order_draft.item.html.twig', [
            'controller_name' => 'OrderItemController',
            'ordersLine'      => $orderLineRepository->findByOrderID($request->get('id')),
            'order'           => $orderServices->getOrderByID($request->get('id')),
            'cartItem'        => $cartItem->getItemCart($this->getUser()->getSocietyID())['0']['quantity']
        ]);
    }
}
