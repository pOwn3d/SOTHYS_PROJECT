<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Services\Cart\CartItem;
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
    * @Route("/{_locale}/panier", name="app_shop", requirements={
    * "_locale"="%app.locales%"
    * })
    */
    public function index(ShopServices $shopServices, CartItem $cartItem): Response
    {
        $society = $this->getUser()->getSocietyID();
        $orders  = $shopServices->getOrderDraft($society);
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity']
        ]);
    }

    /**
     * @Route("/order-publish", name="app_order_publish")
     */
    public function orderPublish(CartItem $cartItem, Request $request, ShopServices $shopServices): Response
    {
        $society = $this->getUser()->getSocietyID();

        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $society = $this->getUser()->getSocietyID();
            $order   = $shopServices->createOrder($society, $form->getData());

            if ($order != null) {
                $this->addFlash($order['type'], $order['msg']);
                return $this->redirectToRoute('app_shop');
            }
            return $this->redirectToRoute('app_order');
        }


        return $this->render('shop/shop.html.twig', [
            'controller_name' => 'ShopController',
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity'],
            'form'            => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

//    /**
//     * @Route("/order-publish", name="app_order_publish")
//     */
//    public function orderSave(ShopServices $shopServices): Response
//    {
//        $society = $this->getUser()->getSocietyID();
//        $order   = $shopServices->createOrder($society);
//
//        if ($order != null) {
//            $this->addFlash($order['type'], $order['msg']);
//            return $this->redirectToRoute('app_shop');
//        }
//
//        return $this->redirectToRoute('app_order');
//    }

    /**
     * @Route("/order-edit/{id}", name="app_order_edit")
     */
    public function orderEdit(Request $request, OrderServices $orderServices, OrderDraftServices $orderDraftServices, ShopServices $shopServices): Response
    {
        $id        = $request->get('id');
        $society   = $this->getUser()->getSocietyID();
        $order     = $orderServices->editOrderID($id);
        $orderLine = $orderServices->editOrderLineID($id);
        $orderDraftServices->editOrderDraft($order, $society, $orderLine);
        $shopServices->deleteOrderLine($id);

        return $this->redirect('/panier');
    }

    /**
     * @Route("/order-delete-item/{id}", name="app_order_product_delete")
     */
    public function orderDeleteItem(Request $request, ShopServices $shopServices): Response
    {
        // TODO : Checker si la société est bien celle de la personne qui supprime le produit.
        $id = $request->get('id');
        $shopServices->deleteItemOrderDraft($id);
        return $this->redirectToRoute('app_shop');
    }


}
