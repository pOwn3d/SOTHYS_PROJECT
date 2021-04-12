<?php

namespace App\Controller;

use App\Services\GammeServices;
use App\Services\ProductServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * @Route("/produit/{id}", name="app_item_id")
     * @param Request         $request
     * @param ProductServices $productServices
     * @param GammeServices   $gammeServices
     *
     * @return Response
     */
    public function index(Request $request, ProductServices $productServices, GammeServices $gammeServices): Response
    {

        $id      = $request->get('id');
        $product = $productServices->getProductInfo($id);
        $gamme = $gammeServices->getGammeID($product->getGamme()->getId());


        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'product'         => $product,
            'gamme' => $gamme
        ]);

    }
}
