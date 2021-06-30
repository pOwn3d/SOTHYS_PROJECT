<?php


namespace App\Services;


use App\Entity\Order;
use App\Entity\OrderDraft;
use App\Repository\OrderDraftRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderDraftServices
{
    private OrderDraftRepository $orderDraftRepository;
    private EntityManagerInterface $em;

    public function __construct(OrderDraftRepository $orderDraftRepository, EntityManagerInterface $em)
    {
        $this->orderDraftRepository = $orderDraftRepository;
        $this->em                   = $em;
    }

    public function getOrderDraftID($id, $item)
    {
        return $this->orderDraftRepository->findOneBy([ 'idSociety' => $id, 'idItem' => $item ]);
    }

    public function getAllOrderDraftID($id, $item)
    {
        return $this->orderDraftRepository->findBy([ 'idSociety' => $id, 'idItem' => $item ]);
    }

    public function getAllOrderDraft($society)
    {
        return $this->orderDraftRepository->findBy([ 'idSociety' => $society]);
    }

    public function getAllOrderDraftPromoID($society)
    {
        return $this->orderDraftRepository->findOrderDraftSocietyPromo($society);
    }

    public function getSumOrderDraft($id, $promo)
    {
        return $this->orderDraftRepository->findSumOrderDraftSociety($id, $promo);
    }

    public function getSumItemOrderDraft($id)
    {
        return $this->orderDraftRepository->findSumItemOrderDraftSociety($id);
    }
}
