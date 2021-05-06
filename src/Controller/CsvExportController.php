<?php

namespace App\Controller;

use App\Repository\OrderLineRepository;
use App\Services\CsvExporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CsvExportController extends AbstractController
{




    /**
     * @Route("/csv-export", name="csv_export")
     */
    public function index(CsvExporter $csvExporter): Response
    {

        $csvExporter->exportCoedi();
        dd();

        return $this->render('csv_export/index.html.twig', [
            'controller_name' => 'CsvExportController',
        ]);
    }
}
