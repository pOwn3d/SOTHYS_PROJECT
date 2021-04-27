<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\OrderDraftServices;
use App\Services\SessionServices;
use App\Services\ShopServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/mes-commandes", name="app_order")
     * @param OrderRepository $orderRepository
     *
     * @return Response
     */
    public function index(OrderRepository $orderRepository, SessionServices $sessionServices): Response
    {
        if ($this->getUser()->getSocietyID() != null) {
            $idSociety = $this->getUser()->getSocietyID()->getId();
            $orders    = $orderRepository->findOrderCustomer($idSociety);
        } else {
            $orders = null;
        }



        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'orders'          => $orders,
            'itemCart'        => $sessionServices->getSession()
        ]);
    }

    /**
     * @Route("/add-to-cart/{item}/{qty}", name="app_add_to_cart_id")
     * @param Request            $request
     * @param ShopServices       $shopServices
     * @param OrderDraftServices $orderDraftServices
     *
     * @return JsonResponse
     */
    public function addToCart(Request $request, ShopServices $shopServices, OrderDraftServices $orderDraftServices): JsonResponse
    {
        $qty     = $request->get('qty');
        $item    = $request->get('item');
        $society = $this->getUser()->getSocietyID();
        $shopServices->cartSociety($society, $item, $qty);
        $orders = $orderDraftServices->getOrderDraftID($society->getId(), $item);
        $sum    = $orderDraftServices->getSumOrderDraft($society->getId());
//        $sessionServices->updateSession($orderDraftServices->getSumItemOrderDraft($society->getId()));

        $data = [
            'total'            => $sum[0]['price'],
            'quantity'         => $orders->getQuantity(),
            'price'            => $orders->getPrice(),
            'quantityBundling' => $orders->getQuantityBundling(),
            'id'               => $orders->getId(),
//            'itemCart'         => $sessionServices->getSession()
        ];

        return new JsonResponse(json_encode($data));
    }

}
