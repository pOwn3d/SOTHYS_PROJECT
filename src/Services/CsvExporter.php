<?php


namespace App\Services;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\SocietyRepository;
use League\Csv\CharsetConverter;
use League\Csv\Writer;
use SplTempFileObject;

class CsvExporter
{

    private OrderLineRepository $orderLineRepository;
    private OrderRepository $orderRepository;
    private SocietyRepository $societyRepository;

    public function __construct(OrderLineRepository $orderLineRepository, OrderRepository $orderRepository, SocietyRepository $societyRepository)
    {
        $this->orderLineRepository = $orderLineRepository;
        $this->orderRepository     = $orderRepository;
        $this->societyRepository   = $societyRepository;
    }

    /**
     */
    public function exportCoedi()
    {
        $orders = $this->orderRepository->findOrderCustomerExport();

        $rows = [];
        foreach ($orders as $order) {

            $society = $this->societyRepository->findSociety($order->getSocietyID()->getId());

            array_push($rows, [ 'E|' . $order->getId() . '|' . $society->getIdCustomer() . '|' . $order->getDateOrder()->format("Ymd") . '|Date livraison demandée|Condition paiement|2|3|3|Code Adresse livraison|||||||||COEDI|2|Urgent Order|' . $order->getReference() . '|' . $order->getEmail() . '|Mode livraison|' . $order->getIncoterm()->getId() . '|' . $order->getIncoterm()->getCity() . '|' ]);
            $orderLines = $this->orderLineRepository->findByOrderID($order->getId());

            foreach ($orderLines as $orderLine) {
                array_push($rows, [ 'L|' . $orderLine->getItemID()->getId() . '|' . $orderLine->getQuantity() . '|' . $orderLine->getPrice() . '|Valeur1 remise/frais|Prix net|Motif gratuit|' . $order->getId() . '|Projet|Valeur2 remise/frais' ]);
            }

        }


        $encoder = (new CharsetConverter())
            ->inputEncoding('utf-8')
            ->outputEncoding('iso-8859-15');

        $writer = Writer::createFromFileObject(new SplTempFileObject());
        $writer->addFormatter($encoder);
        $writer->insertAll($rows); //using an array
        $writer->output('test.csv');
    }

}

