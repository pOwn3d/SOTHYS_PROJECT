<?php

namespace App\Controller;

use App\Repository\OrderLineRepository;
use App\Services\OrderServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{
    /**
     * @Route("/commande/{id}", name="app_order_item_id")
     * @param Request             $request
     * @param OrderLineRepository $orderLineRepository
     * @param OrderServices       $orderServices
     *
     * @return Response
     */
    public function index(Request $request, OrderLineRepository $orderLineRepository, OrderServices $orderServices): Response
    {
        $id        = $request->get('id');
        $orderLine = $orderLineRepository->findAllByX3($id);
        $orderX3   = $orderServices->getOrderByX3($id);
        $orderSum  = $orderServices->getSumOrderLine($id);


        return $this->render('order/order.item.html.twig', [
            'controller_name' => 'OrderItemController',
            'orders'          => $orderLine,
            'orderX3'         => $orderX3,
            'orderSum'        => $orderSum
        ]);
    }
}
