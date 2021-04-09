<?php


namespace App\Services;


use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderServices
{


    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrderByX3($id): ?Order
    {
        return $this->orderRepository->findOneBy([ 'idOrderX3' => $id ]);
    }
}
