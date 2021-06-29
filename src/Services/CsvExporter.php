<?php


namespace App\Services;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class CsvExporter
{

    private OrderLineRepository $orderLineRepository;
    private OrderRepository $orderRepository;
    private EntityManagerInterface $em;

    public function __construct(OrderLineRepository $orderLineRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->orderRepository     = $orderRepository;
        $this->orderLineRepository = $orderLineRepository;
        $this->em                  = $entityManager;
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
                '',
                'COEDI',
                '',
                '2',
                $order->getReference(),
                $order->getEmail(),
                $order->getIncoterm()->getModeTransport()->getIdTransport(),
                $order->getIncoterm()->getReference(),
                $order->getIncoterm()->getCity(),
                ''
            ];

            array_push($rows, implode('|', $orderData));
            $orderLines = $this->orderLineRepository->findByOrderID($order->getId());

            foreach ($orderLines as $orderLine) {
                $lineData = [
                    'L',
                    $orderLine->getItemID()->getItemID(),
                    $orderLine->getQuantity(),
                    $orderLine->getPrice(),
                    $orderLine->getDiscount1(),
                    $orderLine->getDiscountedPrice(),
                    $orderLine->getGratuityCode(),
                    $orderLine->getId(),
                    $orderLine->getCode(),
                    '',
                ];
                array_push($rows, implode('|',$lineData) );
            }

            $order->setIdStatut(3);
            $this->em->persist($order);
        }
        $this->em->flush();

        if(empty($rows)) {
            return;
        }

        file_put_contents($_ENV['EXPORT_FOLDER'] . "/COEDI_ImportCommandes.csv", implode("\n", $rows) . "\n", FILE_APPEND);
    }
}
