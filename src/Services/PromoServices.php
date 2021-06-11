<?php


namespace App\Services;


use App\Entity\OrderDraft;
use App\Repository\ItemPriceRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderDraftRepository;
use App\Repository\OrderLineRepository;
use App\Repository\PromotionItemRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PromoServices
{

    /**
     * @var \App\Repository\PromotionRepository
     */
    private PromotionRepository $promotionRepository;
    /**
     * @var \App\Repository\ItemRepository
     */
    private ItemRepository $itemRepository;
    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private Security $security;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $em;

    private OrderDraftRepository $orderDraftRepository;
    /**
     * @var \App\Repository\ItemPriceRepository
     */
    private ItemPriceRepository $itemPriceRepository;
    /**
     * @var \App\Repository\PromotionItemRepository
     */
    private PromotionItemRepository $promotionItemRepository;
    /**
     * @var \App\Repository\OrderLineRepository
     */
    private OrderLineRepository $orderLineRepository;

    public function __construct(PromotionRepository $promotionRepository, ItemRepository $itemRepository, Security $security, EntityManagerInterface $em, OrderDraftRepository $orderDraftRepository, ItemPriceRepository $itemPriceRepository, PromotionItemRepository $promotionItemRepository, OrderLineRepository $orderLineRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->itemRepository = $itemRepository;
        $this->security = $security;
        $this->orderDraftRepository = $orderDraftRepository;
        $this->em = $em;
        $this->itemPriceRepository = $itemPriceRepository;
        $this->promotionItemRepository = $promotionItemRepository;
        $this->orderLineRepository = $orderLineRepository;
    }

    public function getPromoItem($promoItems)
    {

        foreach ($promoItems->getPromotionItems() as $promoItem) {
            dd($promoItem->getItem());
            $x = $this->itemRepository->findProduct($promoItem->getItem());
            dd($x);
        }
    }

    //   TODO :: ETAPE 1  ::
    public function promoItem($promoItems)
    {
        $order = new OrderDraft();
        foreach ($promoItems as $promoItem) {

            $promo = $this->promotionRepository->findOneBy(["id" => $promoItem->getPromotions()->getOwner()->getId()]);
            $itemInfo = $this->itemRepository->findProduct($promoItem->getItem());
            $order->setIdItem($promoItem->getItem())
                ->setIdSociety($this->security->getUser()->getSocietyID())
                ->setPrice($promoItem->getPrice())
                ->setPriceOrder($promoItem->getPrice())
                ->setQuantity(1)
                ->setQuantityBundling($itemInfo->getAmountBulking())
                ->setState(0)
                ->setPromo(1)
                ->setPromotionId($promo)
                ->setFreeRules(0);
            $this->em->persist($order);
            $this->em->flush();
        }
    }

    //  TODO :: ETAPE 2  ::
    public function promoItemRules($promoRules)
    {
        foreach ($promoRules as $promoRule) {
            if ($promoRule->getQtyPurchased() != null || $promoRule->getQtyFree() != null) {
                $item = $this->itemRepository->findOneBy(["id" => $promoRule->getIdItemPurchased()]);
                $itemPrice = $this->itemPriceRepository->findOneBy(["id" => $item]);
                $promo = $this->promotionRepository->findOneBy(["id" => $promoRule->getPromotions()->getValues()[0]]);

                $order = new OrderDraft();
                $order->setIdItem($item)
                    ->setIdSociety($this->security->getUser()->getSocietyID())
                    ->setPrice($itemPrice->getPrice())
                    ->setPriceOrder($promoRule->getQtyPurchased() * $itemPrice->getPrice())
                    ->setQuantity($promoRule->getQtyPurchased())
                    ->setQuantityBundling($item->getAmountBulking())
                    ->setState(0)
                    ->setPromo(1)
                    ->setPromotionId($promo)
                    ->setFreeRules(1);
            }
            $this->em->persist($order);
            $this->em->flush();
        }
    }


    public function promoItemFreeAdd($promoRule, $qty, $itemId)
    {
        $cart = $this->orderDraftRepository->findBy(['idSociety' => $this->security->getUser()->getSocietyID()]);
        foreach ($cart as $item) {
            if ($item->getIdItem()->getId() == $promoRule->getIdItemFree()->getId() && $item->getPrice() == 0) {

                $qty = $promoRule->getQtyFree() * floor($qty / $promoRule->getQtyPurchased());
                $order = $item->setQuantity($qty);

                $this->em->persist($order);
                $this->em->flush();

            }
        }
    }


    // TODO :: ETAPE 3  ::
    public function promoItemFree($promoRule, ?string $qty)
    {
        $order = new OrderDraft();
        if ($qty != null && ($promoRule->getQtyPurchased() % $qty > 0 || $promoRule->getQtyPurchased() == $qty)) {
            $promo = $this->promotionRepository->findOneBy(["id" => $promoRule->getPromotions()->getValues()[0]]);

            $order->setIdItem($promoRule->getIdItemFree())
                ->setIdSociety($this->security->getUser()->getSocietyID())
                ->setPrice(0)
                ->setPriceOrder(0)
                ->setQuantity($qty / $promoRule->getQtyPurchased() * $promoRule->getQtyFree())
                ->setQuantityBundling($promoRule->getIdItemFree()->getAmountBulking())
                ->setState(0)
                ->setPromo(1)
                ->setPromotionId($promo)
                ->setFreeRules(1);
            $this->em->persist($order);
            $this->em->flush();

        } else {

            if ($qty == null) $qty = 1;
            $orderSum = $this->orderDraftRepository->findSumOrderDraftSociety($this->security->getUser()->getSocietyID(), true);
            if ($orderSum[0]['price'] >= $promoRule->getAmountPurchasedMin() && $orderSum[0]['price'] <= $promoRule->getAmountPurchasedMax()) {
                $item = $this->itemRepository->findOneBy(["id" => $promoRule->getIdItemPurchased()]);
                $itemPrice = $this->itemPriceRepository->findOneBy(["id" => $item]);
                $priceItem = $itemPrice->getPrice() - ($itemPrice->getPrice() * $promoRule->getAmountFree() / 100);
                $order->setIdItem($promoRule->getIdItemFree())
                    ->setIdSociety($this->security->getUser()->getSocietyID())
                    ->setPrice($priceItem)
                    ->setPriceOrder($priceItem * $qty)
                    ->setQuantity($qty)
                    ->setQuantityBundling($promoRule->getIdItemFree()->getAmountBulking())
                    ->setState(0)
                    ->setPromo(1)
                    ->setFreeRules(1);
                $this->em->persist($order);
                $this->em->flush();
            }
        }
    }

    public function addtoCartPromo($id)
    {
        $promo = $this->promotionRepository->findOneBy(["id" => $id]);
        $promoItem = $this->promotionItemRepository->findOneBy(["id" => $id]);

        // Récupérer les items de la promo pour les enregistrer
        $carts = $this->orderDraftRepository->findOrderDraftSocietyPromo($this->security->getUser()->getSocietyID());

        if (!empty($carts)) {
            foreach ($carts as $cart) {
                $this->em->remove($cart);
                $this->em->flush();
            }
        }

        // TODO :: ETAPE 1  ::  -- Enregistrer le(s) produit(s) en promotion qui ce trouve dans promotion_item
        $this->promoItem($promo->getPromotionItem());

        //  TODO :: ETAPE 2  ::  -- Enregistrer les produits dans Free_Rules en fonction des conditions de gratuité
        $this->promoItemRules($promo->getFreeRules());

        // TODO ::  ETAPE 3 :: REGLE DE GRATUITE Ajout des items gratuits en fonction du nombre d'item commander
        foreach ($promo->getFreeRules() as $promoRule) {
            $this->promoItemFree($promoRule, $promoRule->getQtyPurchased());
        }



    }


    public function getPromoSociety()
    {

        $society = $this->security->getUser()->getSocietyID()->getId();

        $promos = $this->promotionRepository->findAll(["society" => $society]);

        $promoItem = [];
        foreach ($promos as $promo) {
            $product = $this->itemRepository->findOneBy(["id" => $promo]);
            array_push($promoItem, $product);

        }

        return $promoItem;

    }

}
