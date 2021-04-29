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
        $this->session              = $session;
    }

    public function getItemCart($society)
    {
        $qty = $this->orderDraftRepository->findSumItemOrderDraftSociety($society);
        $this->session->set('itemNumber', $qty);
        return $this->session->get('itemNumber');

    }


}
