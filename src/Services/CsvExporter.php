<?php


namespace App\Services;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use League\Csv\Writer;
use SplTempFileObject;

class CsvExporter
{

    private OrderLineRepository $orderLineRepository;
    private OrderRepository $orderRepository;

    public function __construct(OrderLineRepository $orderLineRepository, OrderRepository $orderRepository)
    {
        $this->orderLineRepository = $orderLineRepository;
        $this->orderRepository     = $orderRepository;
    }

    /**
     */
    public function exportCoedi()
    {
        $orders = $this->orderRepository->findOrderCustomerExport();

        foreach ($orders as $order) {

            $orderLines = $this->orderLineRepository->findByOrderID($order->getId());
            $rows       = [ [ 'E|' . $order->getId() . '|310262|20201214|20201225|VIR60DDF|2|3|3|L01|||||||||COEDI||2|Urgent Order|310262|9|EXW|USSAC||' ] ];

            foreach ($orderLines as $orderLine) {
                array_push($rows, [ 'L|' . $orderLine->getItemID()->getId() . '|' . $orderLine->getQuantity() . '|' . $orderLine->getPrice() . '|Valeur1 remise/frais|Prix net|Motif gratuit|ID COEDI|Projet|Valeur2 remise/frais' ]);
            }

        }

        $writer = Writer::createFromFileObject(new SplTempFileObject());
        $writer->insertAll($rows); //using an array
        $writer->output('test.csv');
    }

}

