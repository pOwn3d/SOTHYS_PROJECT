<?php

namespace App\Controller;

use App\Services\Cart\CartItem;
use App\Services\GammeProductServices;
use App\Services\GammeServices;
use App\Services\GenericNameServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GammeController extends AbstractController
{
    /**
     * @Route("/{_locale}/gamme/{gamme_id}/page/{page?1}", name="app_gamme", requirements={
     *      "_locale"="%app.locales%"
     * })
     * @param Request $request
     * @param GammeServices $gammeServices
     * @param GammeProductServices $gammeProductServices
     * @param CartItem $cartItem
     * @param \App\Services\GenericNameServices $genericNameServices
     * @return Response
     */
    public function index(Request $request, GammeServices $gammeServices, GammeProductServices $gammeProductServices, CartItem $cartItem, GenericNameServices $genericNameServices): Response
    {
        $page = $request->attributes->getInt('page');
        $genericName  =  $genericNameServices->allGenericName();
        $products = $gammeProductServices->findProductsByGenericName($request->get('gamme_id'), $this->getUser()->getSocietyID()->getId(), $page, $genericName);
        $pagination = $gammeProductServices->getPaginationByGenericname($request->get('gamme_id'), $this->getUser()->getSocietyID()->getId(), $page);
        return $this->render('gamme/index.html.twig', [
            'controller_name' => 'GammeController',
            'gamme'           => $gammeServices->getGammeID($request->get('gamme_id')),
            'products'        => $products,
            'pagination'      => $pagination,
            'cartItem'        => $cartItem->getItemCart($this->getUser()->getSocietyID()->getId()),
        ]);
    }
}
