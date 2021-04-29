<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
use App\Services\GammeProductServices;
use App\Services\GammeServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GammeController extends AbstractController
{
    /**
     * @Route("/gamme/{gamme_id}", name="app_gamme")
     * @param Request              $request
     * @param GammeServices        $gammeServices
     * @param GammeProductServices $gammeProductServices
     * @param CartItem             $cartItem
     *
     * @return Response
     */
    public function index(Request $request, GammeServices $gammeServices, GammeProductServices $gammeProductServices, CartItem $cartItem): Response
    {
        return $this->render('gamme/index.html.twig', [
            'controller_name' => 'GammeController',
            'gamme'           => $gammeServices->getGammeID($request->get('gamme_id')),
            'products'        => $gammeProductServices->findByGammeId($request->get('gamme_id'), $this->getUser()->getSocietyID()),
            'cartItem'        => $cartItem->getItemCart($this->getUser()->getSocietyID())['0']['quantity']
        ]);
    }
}
