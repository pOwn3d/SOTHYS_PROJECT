<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     * @param OrderRepository $orderRepository
     *
     * @return Response
     */
    public function index(OrderRepository $orderRepository): Response
    {

        $idSociety = $this->getUser()->getSocietyID()->getId();
        $order     = $orderRepository->findOrderCustomer($idSociety);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'orders'          => $order
        ]);
    }
}
