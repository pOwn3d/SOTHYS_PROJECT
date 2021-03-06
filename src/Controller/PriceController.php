<?php

namespace App\Controller;

use App\Services\PriceService;
use Dompdf\Dompdf;
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
    public function downloadAllPrice(Request $request, PriceService $priceService): Response
    {
        $dompdf = new Dompdf();

        $societyId = $this->getUser()->getSocietyId()->getId();
        $prices = $priceService->getAllPrices($societyId, $request->getLocale());

        $html = $this->renderView('price/all.html.twig', [
            'societyId' => $societyId,
            'prices' => $prices,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("mypdf.pdf", [
                "Attachment" => true
        ]);
    }

    /**
     * @Route("/{_locale}/price/plv", name="app_price_plv")
     */
    public function downloadPlvPrice(Request $request, PriceService $priceService): Response
    {
        $dompdf = new Dompdf();

        $societyId = $this->getUser()->getSocietyId()->getId();
        $prices = $priceService->getPlvPrices($societyId, $request->getLocale());

        $html = $this->renderView('price/plv.html.twig', [
            'societyId' => $societyId,
            'prices' => $prices,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("mypdf.pdf", [
                "Attachment" => true
        ]);
    }
}
