<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceController extends AbstractController
{
    /**
     * @Route("/price", name="app_price")
     */
    public function index(): Response
    {
        return $this->render('price/index.html.twig', [
        ]);
    }

    /**
     * @Route("/price/all", name="app_price_all")
     */
    public function downloadAllPrice(): Response
    {
        return $this->render('price/index.html.twig', [
        ]);
    }

    /**
     * @Route("/price/plv", name="app_price_plv")
     */
    public function downloadPLVPrice(): Response
    {
        return $this->render('price/index.html.twig', [
        ]);
    }
}
