<?php

namespace App\Controller;

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
     *
     * @return Response
     */
    public function index(Request $request, ProductServices $productServices): Response
    {

        $id      = $request->get('id');
        $product = $productServices->getProductInfo($id);

        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'product'         => $product
        ]);
    }
}
