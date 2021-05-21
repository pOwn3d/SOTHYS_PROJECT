<?php


namespace App\Services;


use App\Entity\OrderDraft;
use App\Repository\PromotionRepository;

class PromoServices
{

    /**
     * @var \App\Repository\PromotionRepository
     */
    private PromotionRepository $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository){
        $this->promotionRepository         = $promotionRepository;
    }

    public function addtoCartPromo($id){

        $item =  $this->promotionRepository->findOneBy(["id" => $id]);

//        $item->get
//
//        $order = new OrderDraft();
//        $order->setIdItem($item->getId())
//            ->setIdSociety($society)
//            ->setPrice($price->getPrice())
//            ->setPriceOrder($price->getPrice() * $qty)
//            ->setQuantity($qty)
//            ->setQuantityBundling($quantity)
//            ->setState(0)
//        ;
//        dd($x);

    }

}
