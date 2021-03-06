<?php

namespace App\Controller;

use App\Services\CsvImporter;
use Exception;
use League\Csv\InvalidArgument;
use League\Csv\UnableToProcessCsv;
use League\Csv\UnavailableFeature;
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
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->resetPasswordHelper = $resetPasswordHelper;
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
     * @Route("/import-society-address", name="import_csv_society_address")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importSocietyAddress(CsvImporter $csvImporter): Response
    {
        $csvImporter->importSocietyAddress();
        dd();
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
        $shouldSendMail = (bool)$request->get('should_send_mail');
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
    public function importOrder(CsvImporter $csvImporter): Response
    {
        $csvImporter->importOrder();
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
     * @Route("/import-order-item", name="import_csv_order_item")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importItem(CsvImporter $csvImporter): Response
    {
        $csvImporter->importItem();
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
     * @Route("/import-price-item", name="import_csv_price_item")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importItemPrice(CsvImporter $csvImporter): Response
    {
        $csvImporter->importItemPrice();
        dd();
    }


    /**
     * @Route("/import-quantity-item", name="import_csv_quantity_item")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importItemQuantity(CsvImporter $csvImporter): Response
    {
        $csvImporter->importItemQuantity();
        dd();
    }

    /**
     * @Route("/import-mode-transport", name="import_csv_modetransport")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importModeTransport(CsvImporter $csvImporter): Response
    {
        $csvImporter->importModeTransport();
        dd();
    }

    /**
     * @Route("/import-incoterm", name="import_csv_incoterm")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importIncoterm(CsvImporter $csvImporter): Response
    {
        $csvImporter->importIncoterm();
        dd();
    }

    /**
     * @Route("/import-customer-incoterm", name="import_csv_customer-incoterm")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function customerImportIncoterm(CsvImporter $csvImporter): Response
    {
        $csvImporter->customerImportIncoterm();
        dd();
    }

    /**
     * @Route("/import-payment-method", name="import_payment_method")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importPaymentMethod(CsvImporter $csvImporter): Response
    {
        $csvImporter->importPaymentMethod();
        dd();
    }

    /**
     * @Route("/import-generic-name", name="import_generic_name")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importGenericName(CsvImporter $csvImporter): Response
    {
        $csvImporter->importGenericName();
        dd();
    }

    /**
     * @Route("/import-remove-old-item", name="import_remove")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function removeCSV(CsvImporter $csvImporter): Response
    {
        $csvImporter->remove();
        dd();
    }

    /**
     * @Route("/import-free-restocking-rules", name="import_freerestocking_rules")
     * @param CsvImporter $csvImporter
     *
     * @return Response
     */
    public function importFreeRestockingRules(CsvImporter $csvImporter): Response
    {
        try {
            $csvImporter->freeRulesReasort();
        } catch (InvalidArgument | UnavailableFeature | UnableToProcessCsv $e) {
        }
        dd();
    }
}
