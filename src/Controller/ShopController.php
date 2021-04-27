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

        return $this->redirectToRoute('app_order');
    }

    /**
     * @Route("/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, OrderDraftServices $orderDraftServices): Response
    {
        $id        = $request->get('id');
        $society   = $this->getUser()->getSocietyID();
        $order     = $orderServices->editOrderID($id);
        $orderLine = $orderServices->editOrderLineID($id);
        $orderDraftServices->editOrderDraft($order, $society, $orderLine);

        return $this->redirect('/shop');
    }

    /**
     * @Route("/order-delete-item/{id}", name="app_order_product_delete")
     */
    public function orderDeleteItem(Request $request, ShopServices $shopServices): Response
    {
        // TODO : Checker si la société est bien celle de la personne qui supprime le produit.
        $id = $request->get('id');
        $shopServices->deleteItemOrderDraf($id);
        return $this->redirect('/shop');
    }


}
