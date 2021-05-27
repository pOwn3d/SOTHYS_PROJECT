<?php

namespace App\Controller;

use App\Repository\PromotionItemRepository;
use App\Services\Cart\CartItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_home")
     * @param CartItem $cartItem
     * @param \App\Repository\PromotionItemRepository $promotionItemRepository
     * @return Response
     */
    public function index(CartItem $cartItem, PromotionItemRepository $promotionItemRepository): Response
    {
        $promos = $promotionItemRepository->findAll();
        $society = $this->getUser()->getSocietyID()->getId();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity'],
            'promos' => $promos
        ]);
    }
}
