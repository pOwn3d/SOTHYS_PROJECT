<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(OrderRepository $orderRepository): Response
    {


        if ($this->getUser()->getSocietyID() != null) {
            $idSociety = $this->getUser()->getSocietyID()->getId();
            $order     = $orderRepository->findOrderCustomer($idSociety);
        } else {
            $order = null;
        }


        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'orders' => $order
        ]);
    }
}
