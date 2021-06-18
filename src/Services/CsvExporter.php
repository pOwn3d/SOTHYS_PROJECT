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

            $orderData = [
                'E',
                $order->getId(),
                $order->getSocietyID()->getIdCustomer(),
                $order->getDateOrder()->format("Ymd"),
                $order->getDateDelivery()->format("Ymd"),
                $order->getSocietyID()->getPaymentMethod()->getIdX3(),
                '2',
                '3',
                '3',
                $order->getAddress()->getLabel(),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'COEDI',
                '2',
                '',
                $order->getReference(),
                $order->getEmail(),
                $order->getIncoterm()->getModeTransport()->getId(), // Id transport a clarifié
                $order->getIncoterm()->getId(),
                $order->getIncoterm()->getCity(),
                ''
            ];

            array_push($rows, [implode('|', $orderData)]);
            $orderLines = $this->orderLineRepository->findByOrderID($order->getId());

            foreach ($orderLines as $orderLine) {
                $lineData = [
                    'L',
                    $orderLine->getItemID()->getId(),
                    $orderLine->getQuantity(),
                    $orderLine->getPrice(),
                    $orderLine->getDiscount1(),
                    $orderLine->getDiscountedPrice(),
                    $orderLine->getGratuityCode(),
                    $order->getId(),
                    $orderLine->getCode(),
                    $orderLine->getDiscount2(),
                ];
                array_push($rows, [ implode('|',$lineData) ]);
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

