<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderDraft;
use App\Entity\OrderLine;
use App\Entity\Society;
use App\Repository\FreeRestockingRulesRepository;
use App\Repository\IncotermRepository;
use App\Repository\ItemPriceRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderDraftRepository;
use App\Repository\OrderLineRepository;
use App\Repository\PromotionItemRepository;
use App\Repository\PromotionRepository;
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
    private SocietyRepository $societyRepository;
    private PromoServices $promoServices;
    private PromotionRepository $promotionRepository;
    private FreeRestockingRulesRepository $freeRestockingRulesRepository;
    /**
     * @var \App\Services\FreeRestockingRulesService
     */
    private FreeRestockingRulesService $freeRestockingRulesService;


    public function __construct(
        ItemRepository $itemRepository,
        ItemPriceRepository $itemPriceRepository,
        OrderDraftRepository $orderDraftRepository,
        EntityManagerInterface $em,
        ItemQuantityService $itemQuantityService,
        OrderLineRepository $orderLineRepository,
        IncotermRepository $incotermRepository,
        PromotionItemRepository $promotionItemRepository,
        SocietyRepository $societyRepository,
        PromoServices $promoServices,
        PromotionRepository $promotionRepository,
        FreeRestockingRulesRepository $freeRestockingRulesRepository,
        FreeRestockingRulesService $freeRestockingRulesService
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
        $this->promoServices = $promoServices;
        $this->promotionRepository = $promotionRepository;
        $this->freeRestockingRulesRepository = $freeRestockingRulesRepository;
        $this->freeRestockingRulesService = $freeRestockingRulesService;
    }

    public function getPriceItemIDSociety($item, $society)
    {
        return $this->itemPriceRepository->getItemPriceBySociety($item, $society);
    }

    public function addToCart(Society $society, $itemId, $qty, $promoID)
    {
        $societyId = $society->getId();
        $promo = $this->promotionRepository->findOneBy(["id" => $promoID]);

        if ($promo != null) {
            foreach ($promo->getFreeRules() as $promoRule) {
                $this->promoServices->promoItemFreeAdd($promoRule, $qty, $itemId);
            }
        }

        $item = $this->itemRepository->findOneBy(['id' => $itemId]);
        $cart = $this->orderDraftRepository->findOneBy(['idSociety' => $societyId]);
        $society = $this->societyRepository->findOneBy(['id' => $societyId]);
        $itemPrice = $this->itemPriceRepository->getItemPriceBySociety($itemId, $societyId);

        if (empty($item) || empty($itemPrice)) {
            throw new \Exception("No item found with this id ");
        }

        $cartItem = $this->orderDraftRepository->findOneBy([ 'idItem' => $item->getId() ]);

        if ($cart == null || $cartItem == null) {
            $order = new OrderDraft();
            $order->setIdItem($item)
                ->setIdSociety($society)
                ->setPrice($itemPrice->getPrice())
                ->setPriceOrder($itemPrice->getPrice() * $qty)
                ->setQuantity($qty)
                ->setQuantityBundling($item->getAmountBulking())
                ->setState(0)
                ->setPromo(0)
                ->setPromotionId($promo);
            $this->em->persist($order);
            $this->em->flush();
        }

        if ($cartItem != null) {
            $order = $cartItem;
            $order->setQuantity($qty)
                ->setPriceOrder($order->getPrice() * $qty);
            $this->em->persist($order);
            $this->em->flush();
        }
    }

    public function addToCartRestocking(Society $society, $itemId, $qty)
    {

        $item = $this->itemRepository->findOneBy(['id' => $itemId]);
        $cart = $this->orderDraftRepository->findBy(['idSociety' => $society]);
        $society = $this->societyRepository->findOneBy(['id' => $society]);
        $rule = $this->freeRestockingRulesRepository->findOneBy(['societyId' => $society]);
        $itemPrice = $this->itemPriceRepository->getItemPriceBySociety($itemId, $society->getId());
        $totalOrder = 0;

        foreach ($cart as $order) {
            if (str_contains($rule->getTypeOfRule(), $order->getIdItem()->getIdPresentation()) == true) {
                $totalOrder += $order->getPriceOrder() * $rule->getValueRule() / 100;
            }

            if (intval($order->getPriceOrder()) == 0) {
                $totalOrder = $totalOrder - ($order->getPrice() * $order->getQuantity());
            }
        }

        if ($itemPrice->getPrice() * $qty > $totalOrder) {
            $this->addFlash('error', 'Dépassement du nombre de produit gratuit');
        } else {

            $ifexist = false;
            if ($totalOrder >= 0) {
                foreach ($cart as $order) {
                    if ($order->getIdItem() === $item) {
                        $order->setPriceOrder(0)
                            ->setQuantity($qty);
                        $totalOrder = $totalOrder - ($order->getPrice() * $order->getQuantity());
                        $ifexist = true;
                        $this->em->persist($order);
                        $this->em->flush();
                    }
                }
                if (!$ifexist) {
                    $order = new OrderDraft();
                    $order->setIdItem($item)
                        ->setIdSociety($society)
                        ->setPrice($itemPrice->getPrice())
                        ->setPriceOrder(0)
                        ->setQuantity($qty)
                        ->setQuantityBundling($item->getAmountBulking())
                        ->setState(0)
                        ->setPromo(0)
                        ->setPromotionId(null);
                    $this->em->persist($order);
                    $this->em->flush();
                }
            }
        }

    }

    public function getOrderDraft($society)
    {
        return $this->orderDraftRepository->findOrderDraftSociety($society);
    }

    public function getOrderDraftPromo($society)
    {
        return $this->orderDraftRepository->findOrderDraftSocietyPromo($society);
    }

    public function createOrder($user, $society, $data, $promo)
    {
        $orders = $this->orderDraftRepository->findOrderDraft($society->getId(), $promo);
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
            ->setDateDelivery($data->getDateDelivery())
            ->setSocietyID($society)
            ->setIdStatut($data->getIdStatut())
            ->setIncoterm($data->getIncoterm())
            ->setReference($data->getReference())
            ->setAddress($data->getAddress())
            ->setEmail($user->getEmail())
            ->setPaymentMethod($data->getPaymentMethod());

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
                ->setPriceUnit($order->getPrice())//                ->setRemainingQtyOrder()
            ;

            $this->em->remove($order);
            $this->em->persist($newOrderLine);
            $this->em->flush();
        }
    }

    public function updateOrder($order, $data)
    {
        $order->setDateDelivery($data->getDateDelivery())
            ->setIdStatut($data->getIdStatut())
            ->setIncoterm($data->getIncoterm())
            ->setReference($data->getReference())
            ->setAddress($data->getAddress());

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

    public function updateQuantityOrderLineById($orderLineId, $quantity)
    {

        $orderLine = $this->orderLineRepository->findOneBy([
            'id' => $orderLineId,
        ]);

        $orderLine->setQuantity($quantity);

        $this->em->persist($orderLine);
        $this->em->flush();
    }

    public function deleteItemOrderDraft($id)
    {
        $orders = $this->orderDraftRepository->findOneBy(['id' => $id]);
        $this->em->remove($orders);
        $this->em->flush();
    }

    public function deleteOrderLine($id)
    {
        $this->orderLineRepository->deleteOrderLine($id);
    }

    public function deleteOrderLineById($id)
    {
        $orderLine = $this->orderLineRepository->findOneBy([
            'id' => $id,
        ]);

        $this->em->remove($orderLine);
        $this->em->flush();
    }

    public function emptyCart($societyId)
    {

        $orderDrafts = $this->orderDraftRepository->findBy([
            'idSociety' => $societyId,
        ]);

        foreach ($orderDrafts as $orderDraft) {
            $this->em->remove($orderDraft);
        }

        $this->em->flush();
    }

    public function getFreeRestockingRules($society)
    {
        return $this->freeRestockingRulesRepository->freeRestockingRulesSociety($society);
    }

}
