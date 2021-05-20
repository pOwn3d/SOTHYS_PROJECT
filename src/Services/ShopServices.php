<?php


namespace App\Services;

use App\Entity\Incoterm;
use App\Entity\Order;
use App\Entity\OrderDraft;
use App\Entity\OrderLine;
use App\Repository\GammeProductRepository;
use App\Repository\IncotermRepository;
use App\Repository\ItemPriceRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderDraftRepository;
use App\Repository\OrderLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopServices extends AbstractController
{
    private ItemRepository $itemRepository;
    private ItemPriceRepository $itemPriceRepository;
    private OrderDraftRepository $orderDraftRepository;
    private EntityManagerInterface $em;
    private ItemQuantityService $itemQuantityService;
    private OrderLineRepository $orderLineRepository;
    private IncotermRepository $incotermRepository;
    private GammeProductRepository $gammeProductRepository;


    public function __construct(ItemRepository $itemRepository, GammeProductRepository $gammeProductRepository, ItemPriceRepository $itemPriceRepository, OrderDraftRepository $orderDraftRepository, EntityManagerInterface $em, ItemQuantityService $itemQuantityService, OrderLineRepository $orderLineRepository, IncotermRepository $incotermRepository)
    {
        $this->itemRepository         = $itemRepository;
        $this->gammeProductRepository = $gammeProductRepository;
        $this->itemPriceRepository    = $itemPriceRepository;
        $this->orderDraftRepository   = $orderDraftRepository;
        $this->em                     = $em;
        $this->itemQuantityService    = $itemQuantityService;
        $this->orderLineRepository    = $orderLineRepository;
        $this->incotermRepository    = $incotermRepository;
    }


//    public function getItemShop()
//    {
//        return $this->itemRepository->findAll();
//    }
//
//    public function getItemGammeShop()
//    {
//        return $this->gammeProductRepository->findAll();
//    }

    public function getPriceItemIDSociety($item, $society)
    {
        return $this->itemPriceRepository->getPriceBySociety($item, $society);
    }

    public function cartSociety($society, $item, $qty)
    {
        $itemID   = $this->itemRepository->findOneBy([ 'id' => $item ]);
        $cart     = $this->orderDraftRepository->findOneBy([ 'idSociety' => $society ]);
        if(empty($itemID)){
            throw new \Exception("no item found with this id ");
        }
        $cartItem = $this->orderDraftRepository->findOneBy([ 'idItem' => $itemID->getId() ]);
        $price    = $this->itemPriceRepository->getPriceBySociety($item, $society);

        if ($this->itemQuantityService->quantityItemSociety($item, $society) == null) {
            $quantity = '1';
        } else {
            $quantity = $this->itemQuantityService->quantityItemSociety($item, $society)->getQuantity();
        }


        if ($cart == null || $cartItem == null) {
            $order = new OrderDraft();
            $order->setIdItem($itemID)
                ->setIdSociety($society)
                ->setPrice($price->getPrice())
                ->setPriceOrder($price->getPrice() * $qty)
                ->setQuantity($qty)
                ->setQuantityBundling($quantity)
                ->setState(0);
        }

        if ($cartItem != null) {
            $order = $cartItem;
            $order->setQuantity($qty)
                ->setPriceOrder($price->getPrice() * $qty);
        }

        $this->em->persist($order);
        $this->em->flush();
    }

    public function getOrderDraft($society)
    {
        return $this->orderDraftRepository->findOrderDraftSociety($society);
    }

    public function createOrder($society,  $data)
    {

//dd(->getId());
        $orders = $this->orderDraftRepository->findBy([ 'idSociety' => $society->getId() ]);
        $incoterm = $this->incotermRepository->findBy([ 'id' => $society->getId() ]);
        if ($orders == []) {
            return [
                'type' => 'error',
                'msg'  => 'Commande vide',
            ];
        }

        $total = 0;
        foreach ($orders as $order) {
            $total += $order->getPrice() * $order->getQuantity();
        }

        $newOrder = new Order();
        $newOrder
//                ->setIdOrderX3()
//                ->setIdOrder(666)
//                ->setIdDownStatut()
            ->setPriceOrder($total)
            ->setDateOrder(new \DateTime())
//                ->setDateDelivery()
            ->setSocietyID($society)
            ->setIdStatut(1)
            ->setIncoterm($data->getIncoterm())
            ->setReference($data->getReference())
            ->setEmail($data->getEmail())
        ;

        $this->em->persist($newOrder);
        $this->em->flush();
        $orderId = $newOrder->getId();

        foreach ($orders as $order) {

            $newOrderLine = new OrderLine();
            $newOrderLine
                ->setQuantity($order->getQuantity())
                ->setPrice($order->getPrice() * $order->getQuantity())
                ->setPriceUnit($order->getPrice())
                ->setItemID($order->getIdItem())
                ->setIdOrder($orderId)
//                ->setIdOrderLine()
//                ->setIdOrderX3()
                ->setPriceUnit($order->getPrice())//                ->setRemainingQtyOrder()
            ;

            $this->em->remove($order);
            $this->em->persist($newOrderLine);
            $this->em->flush();
        }

    }

    public function deleteItemOrderDraft($id)
    {
        $orders = $this->orderDraftRepository->findOneBy([ 'id' => $id ]);
        $this->em->remove($orders);
        $this->em->flush();
    }

    public function deleteOrderLine($id)
    {
        $this->orderLineRepository->deleteOrderLine($id);
    }

}
