<?php

namespace App\Controller;

use App\Services\GammeServices;
use App\Services\ProductServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GammeController extends AbstractController
{
    /**
     * @Route("/gamme/{gamme_id}", name="app_gamme")
     * @param Request         $request
     * @param GammeServices   $gammeServices
     * @param ProductServices $productServices
     *
     * @return Response
     */
    public function index(Request $request, GammeServices $gammeServices, ProductServices $productServices): Response
    {

        $products = $productServices->findByGammeId($request->get('gamme_id'));
        $gamme    = $gammeServices->getGammeID($request->get('gamme_id'));

        return $this->render('gamme/index.html.twig', [
            'controller_name' => 'GammeController',
            'gamme'           => $gamme,
            'products'        => $products
        ]);

    }
}
