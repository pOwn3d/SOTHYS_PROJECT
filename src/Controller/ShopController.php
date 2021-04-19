<?php

namespace App\Controller;

use App\Services\OrderDraftServices;
use App\Services\OrderServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(ShopServices $shopServices): Response
    {

        $society = $this->getUser()->getSocietyID();
        $orders  = $shopServices->getOrderDraft($society);

        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'orders'          => $orders,
        ]);
    }

    /**
     * @Route("/order-publish", name="app_order_publish")
     */
    public function orderPublish(OrderDraftServices $orderDraftServices, ShopServices $shopServices): Response
    {
        $society = $this->getUser()->getSocietyID();
        $shopServices->setOrderSociety($society);
        dd();
    }

    /**
     * @Route("/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, OrderDraftServices $orderDraftServices): Response
    {
        //   je récupère la commande qu'il souhaite éditer

        $id      = $request->get('id');
        $society = $this->getUser()->getSocietyID();
        $order     = $orderServices->editOrderID($id);
        $orderLine = $orderServices->editOrderLineID($id);

        $orderDraft = $orderDraftServices->editOrderDraft($order, $society, $orderLine);

        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'orders'          => $order,
        ]);
    }

}
