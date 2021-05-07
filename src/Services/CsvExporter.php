<?php


namespace App\Services;

use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;
use App\Repository\SocietyRepository;
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

            array_push($rows, [ 'E|' . $order->getId() . '|' . $society->getIdCustomer() . '|Date commande|Date livraison demandée|Condition paiement|2|3|3|Code Adresse livraison|Commentaire1|Commentaire2|Commentaire3|Commentaire4|Commentaire5|Commentaire6|Commentaire7|Commentaire8|COEDI|2|Urgent Order|Réf commande client|adresse mail|Mode livraison|Incoterm|Ville incoterm|' ]);
            $orderLines = $this->orderLineRepository->findByOrderID($order->getId());

            foreach ($orderLines as $orderLine) {
                array_push($rows, [ 'L|' . $orderLine->getItemID()->getId() . '|' . $orderLine->getQuantity() . '|' . $orderLine->getPrice() . '|Valeur1 remise/frais|Prix net|Motif gratuit|ID COEDI|Projet|Valeur2 remise/frais' ]);
            }

        }

        $writer = Writer::createFromFileObject(new SplTempFileObject());
        $writer->insertAll($rows); //using an array
        $writer->output('test.csv');
    }

}

