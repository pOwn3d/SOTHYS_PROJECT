<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PromotionRepository;

class HomeController extends AbstractController
{
    /**
    * @Route("/{_locale}/accueil", name="app_home", requirements={
    * "_locale"="%app.locales%"
    * })
     * @param CartItem $cartItem

     * @return Response
     */
    public function index(CartItem $cartItem, PromotionRepository $promotionItemRepository): Response
    {

        $promos = $promotionItemRepository->findAllValidPromos();

        $society = $this->getUser()->getSocietyID()->getId();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cartItem'        => $cartItem->getItemCart($society)['0']['quantity'],
            'promos'          => $promos
        ]);
    }
}
