<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\Cart\CartItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    /**
     * @Route("/{_locale}/portfolio", name="app_portfolio")
     */
    public function index(OrderRepository $orderRepository, CartItem $cartItem): Response
    {
        $societyId = $this->getUser()->getSocietyID()->getId();
        $orders  = $orderRepository->findPortfolioOrders($societyId);

        return $this->render('portfolio/index.html.twig', [
            'orders'          => $orders,
            'cartItem'        => $cartItem->getItemCart($societyId)
        ]);
    }
}
