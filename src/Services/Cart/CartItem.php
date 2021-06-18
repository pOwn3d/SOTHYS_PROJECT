<?php


namespace App\Services\Cart;


use App\Repository\OrderDraftRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartItem
{

    private OrderDraftRepository $orderDraftRepository;
    private SessionInterface $session;

    public function __construct(OrderDraftRepository $orderDraftRepository, SessionInterface $session)
    {
        $this->orderDraftRepository = $orderDraftRepository;
        $this->session = $session;
    }

    public function getItemCart(?int $societyId): ?int
    {
        $qty = $this->orderDraftRepository->findLineOrderDraftSociety($societyId);

        $this->session->set('itemNumber', count($qty));
        return $this->session->get('itemNumber');

    }

}
