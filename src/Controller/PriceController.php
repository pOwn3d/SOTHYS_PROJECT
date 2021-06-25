<?php

namespace App\Controller;

use App\Services\PriceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{_locale}/price/all", name="app_price_all")
     */
    public function downloadAllPrice(): Response
    {
        return $this->render('price/all.html.twig', [
        ]);
    }

    /**
     * @Route("/{_locale}/price/plv", name="app_price_plv")
     */
    public function downloadPlvPrice(Request $request, PriceService $priceService): Response
    {

        $societyId = $this->getUser()->getSocietyId()->getId();
        $prices = $priceService->getPlvPrices($societyId, $request->getLocale());

        return $this->render('price/plv.html.twig', [
            'societyId' => $societyId,
            'prices' => $prices,
        ]);
    }
}
