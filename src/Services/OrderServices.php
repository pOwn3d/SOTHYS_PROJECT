<?php


namespace App\Services;


use App\Entity\Order;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;

class OrderServices
{

    private OrderRepository $orderRepository;
    private OrderLineRepository $orderLineRepository;

    public function __construct(OrderRepository $orderRepository, OrderLineRepository $orderLineRepository)
    {
        $this->orderRepository     = $orderRepository;
        $this->orderLineRepository = $orderLineRepository;
    }

    public function getOrderByX3($id): ?Order
    {
        return $this->orderRepository->findOneBy([ 'idOrderX3' => $id ]);
    }

    public function getOrderByID($id): ?Order
    {
        return $this->orderRepository->findOneBy([ 'id' => $id ]);
    }

    public function getSumOrderLine($id)
    {
        return $this->orderLineRepository->sumOrderByX3([ 'idOrderX3' => $id ]);
    }

    public function getOrderLinesByID($id): array
    {
        return $this->orderLineRepository->findBy([ 'idOrder' => $id ]);
    }
}
