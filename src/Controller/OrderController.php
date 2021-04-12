<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\OrderServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/mes-commandes", name="app_order")
     * @param OrderRepository $orderRepository
     * @param OrderServices   $orderServices
     *
     * @return Response
     */
    public function index(OrderRepository $orderRepository, OrderServices $orderServices): Response
    {

        if ($this->getUser()->getSocietyID() != null) {
            $idSociety = $this->getUser()->getSocietyID()->getId();
            $orders     = $orderRepository->findOrderCustomer($idSociety);
        } else {
            $orders = null;
        }

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'orders' => $orders,
        ]);

    }
}
