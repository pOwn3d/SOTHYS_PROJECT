<?php


namespace App\Services;


use App\Entity\OrderDraft;
use App\Repository\ItemPriceRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderDraftRepository;
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

    public function __construct(PromotionRepository $promotionRepository, ItemRepository $itemRepository, Security $security, EntityManagerInterface $em, OrderDraftRepository $orderDraftRepository, ItemPriceRepository $itemPriceRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->itemRepository = $itemRepository;
        $this->security = $security;
        $this->orderDraftRepository = $orderDraftRepository;
        $this->em = $em;
        $this->itemPriceRepository = $itemPriceRepository;
    }

    //   TODO :: ETAPE 1  ::
    public function promoItem($promoItems)
    {
        $order = new OrderDraft();
        foreach ($promoItems as $promoItem) {
            $itemInfo = $this->itemRepository->findProduct($promoItem->getItem());
            $order->setIdItem($promoItem->getItem())
                ->setIdSociety($this->security->getUser()->getSocietyID())
                ->setPrice($promoItem->getPrice())
                ->setPriceOrder($promoItem->getPrice())
                ->setQuantity(1)
                ->setQuantityBundling($itemInfo->getAmountBulking())
                ->setState(0)
                ->setPromo(1)
                ->setFreeRules(0);
        }
        $this->em->persist($order);
        $this->em->flush();
    }

    //  TODO :: ETAPE 2  ::
    public function promoItemRules($promoRules)
    {
        foreach ($promoRules as $promoRule) {
            if ($promoRule->getQtyPurchased() != null || $promoRule->getQtyFree() != null) {
                $item = $this->itemRepository->findOneBy(["id" => $promoRule->getIdItemPurchased()]);
                $itemPrice = $this->itemPriceRepository->findOneBy(["id" => $item]);
                $order = new OrderDraft();
                $order->setIdItem($item)
                    ->setIdSociety($this->security->getUser()->getSocietyID())
                    ->setPrice($itemPrice->getPrice())
                    ->setPriceOrder($promoRule->getQtyPurchased() * $itemPrice->getPrice())
                    ->setQuantity($promoRule->getQtyPurchased())
                    ->setQuantityBundling($item->getAmountBulking())
                    ->setState(0)
                    ->setPromo(1)
                    ->setFreeRules(1);
            }
            $this->em->persist($order);
            $this->em->flush();
        }
    }

    // TODO :: ETAPE 3  ::
    public function promoItemFree($promoRule, ?string $qty)
    {
        $order = new OrderDraft();
        if ($qty != null && ($promoRule->getQtyPurchased() % $qty > 0 || $promoRule->getQtyPurchased() == $qty)) {
            $order->setIdItem($promoRule->getIdItemFree())
                ->setIdSociety($this->security->getUser()->getSocietyID())
                ->setPrice(0)
                ->setPriceOrder(0)
                ->setQuantity($qty / $promoRule->getQtyPurchased() * $promoRule->getQtyFree())
                ->setQuantityBundling($promoRule->getIdItemFree()->getAmountBulking())
                ->setState(0)
                ->setPromo(1)
                ->setFreeRules(1);
            $this->em->persist($order);
            $this->em->flush();
        }
    }

    public function addtoCartPromo($id)
    {
        $promo = $this->promotionRepository->findOneBy(["id" => $id]);
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

            // TODO ::  ETAPE 4 :: REGLE DE GRATUITE echelle de prix avec pourcentage de réduction


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
