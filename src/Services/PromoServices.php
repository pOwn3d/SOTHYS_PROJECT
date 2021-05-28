<?php


namespace App\Services;


use App\Entity\OrderDraft;
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
    /**
     * @var \App\Entity\OrderDraft
     */
    private OrderDraftRepository $orderDraftRepository;

    public function __construct(PromotionRepository $promotionRepository, ItemRepository $itemRepository, Security $security, EntityManagerInterface $em, OrderDraftRepository $orderDraftRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->itemRepository = $itemRepository;
        $this->security = $security;
        $this->orderDraftRepository = $orderDraftRepository;

        $this->em = $em;
    }

    public function addtoCartPromo($id)
    {

        $promo = $this->promotionRepository->findOneBy(["id" => $id]);
        $cart = $this->orderDraftRepository->findOrderDraftSocietyPromo($this->security->getUser()->getSocietyID());


        if (!empty($cart)){
            $this->em->remove($cart['0']);
            $this->em->flush();
        }



        $item = $this->itemRepository->findOneBy(["id" => $promo->getPromotionItem()->getValues()[0]->getItem()->getId()]);
        $product = $promo->getPromotionItem()->getValues()[0]->getPrice();

        $order = new OrderDraft();
        $order->setIdItem($item)
            ->setIdSociety($this->security->getUser()->getSocietyID())
            ->setPrice($promo->getPromotionItem()->getValues()[0]->getPrice())
            ->setPriceOrder(0)
            ->setQuantity(1)
            ->setQuantityBundling($item->getAmountBulking())
            ->setState(0)
            ->setPromo(1);


        $this->em->persist($order);
        $this->em->flush();

    }

}
