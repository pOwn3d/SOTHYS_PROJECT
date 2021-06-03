<?php


namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderDraft;
use App\Entity\OrderLine;
use App\Entity\Society;
use App\Repository\IncotermRepository;
use App\Repository\ItemPriceRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderDraftRepository;
use App\Repository\OrderLineRepository;
use App\Repository\PromotionItemRepository;
use App\Repository\SocietyRepository;
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
    private PromotionItemRepository $promotionItemRepository;


    public function __construct(
            ItemRepository $itemRepository,
            ItemPriceRepository $itemPriceRepository,
            OrderDraftRepository $orderDraftRepository,
            EntityManagerInterface $em,
            ItemQuantityService $itemQuantityService,
            OrderLineRepository $orderLineRepository,
            IncotermRepository $incotermRepository,
            PromotionItemRepository $promotionItemRepository,
            SocietyRepository $societyRepository
    ) {
        $this->itemRepository = $itemRepository;

        $this->itemPriceRepository = $itemPriceRepository;
        $this->orderDraftRepository = $orderDraftRepository;
        $this->em = $em;
        $this->itemQuantityService = $itemQuantityService;
        $this->orderLineRepository = $orderLineRepository;
        $this->incotermRepository = $incotermRepository;
        $this->promotionItemRepository = $promotionItemRepository;
        $this->societyRepository = $societyRepository;
    }

    public function getPriceItemIDSociety($item, $society)
    {
        return $this->itemPriceRepository->getPriceBySociety($item, $society);
    }

    public function addToCart(Society $society, $itemId, $qty)
    {
        $societyId = $society->getId();

        $item   = $this->itemRepository->findOneBy([ 'id' => $itemId ]);
        $cart     = $this->orderDraftRepository->findOneBy([ 'idSociety' => $societyId ]);
        $cartItem  = $this->orderDraftRepository->findOneBy(['idItem' => $item->getId() ]);
        $society   = $this->societyRepository->findOneBy(['id' => $societyId]);
        $itemPrice = $this->itemPriceRepository->getItemPriceBySociety($itemId, $societyId);

        if(empty($item) || empty($itemPrice)){
            throw new \Exception("No item found with this id ");
        }
        $cartItem = $this->orderDraftRepository->findOneBy([ 'idItem' => $itemID->getId() ]);

        if ($promo == true) {
            $price = $this->promotionItemRepository->findOneBy(['id' => $item]);
        } else {
            $price = $this->itemPriceRepository->getPriceBySociety($item, $society);
        }

        if ($this->itemQuantityService->quantityItemSociety($item, $society) == null) {
            $quantity = 1;
        } else {
            $quantity = $this->itemQuantityService->quantityItemSociety($item, $society)->getQuantity();
        }

        if ($cart == null || $cartItem == null) {
            $order = new OrderDraft();
            $order->setIdItem($item)
                ->setIdSociety($society)
                ->setPrice($itemPrice->getPrice())
                ->setPriceOrder($itemPrice->getPrice() * $qty)
                ->setQuantity($qty)
                ->setQuantityBundling($quantity)
                ->setState(0)
                ->setPromo(0)
          ;
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

    public function getOrderDraftPromo($society)
    {
        return $this->orderDraftRepository->findOrderDraftSocietyPromo($society);
    }

    public function createOrder($society, $data, $promo)
    {
        $orders = $this->orderDraftRepository->findOrderDraft($society->getId(), $promo);
        $incoterm = $this->incotermRepository->findBy(['id' => $society->getId()]);
        if ($orders == []) {
            return [
                'type' => 'error',
                'msg' => 'Commande vide',
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
                ->setPromo($promo)
//                ->setIdOrderLine()
//                ->setIdOrderX3()
                ->setPriceUnit($order->getPrice())
                //                ->setRemainingQtyOrder()
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
