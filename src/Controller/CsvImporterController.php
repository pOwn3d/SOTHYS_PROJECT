<?php

namespace App\Controller;

use App\Services\CsvImporter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class CsvImporterController extends AbstractController
{

    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * @Route("/import-user/{should_send_mail}", name="import_csv_user")
     * @param CsvImporter $csvImporter
     * @param Request     $request
     *
     * @return Response
     */
    public function index(CsvImporter $csvImporter, Request $request): Response
    {
        $shouldSendMail = (bool) $request->get('should_send_mail');

        $csvImporter->importUser($shouldSendMail);
        dd();
    }

    /**
     * @Route("/import-order", name="import_csv_order")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     * @throws Exception
     */
    public function importBooking(CsvImporter $csvImporter): Response
    {
        $csvImporter->importOrder();
        dd();
    }

    /**
     * @Route("/import-society", name="import_csv_society")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importSociety(CsvImporter $csvImporter): Response
    {
        $csvImporter->importSociety();
        dd();
    }

    /**
     * @Route("/import-gamme", name="import_csv_gamme")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importGamme(CsvImporter $csvImporter): Response
    {
        $csvImporter->importGamme();
        dd();
    }

    /**
     * @Route("/import-order-line", name="import_csv_order_line")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importOrderLine(CsvImporter $csvImporter): Response
    {
        $csvImporter->importOrderLine();
        dd();
    }

    /**
     * @Route("/import-order-product", name="import_csv_order_item")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importItem(CsvImporter $csvImporter): Response
    {
        $csvImporter->importItem();
        dd();
    }
}
