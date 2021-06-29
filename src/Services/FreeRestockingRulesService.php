<?php


namespace App\Services;


use App\Repository\FreeRestockingRulesRepository;

class FreeRestockingRulesService
{

    private FreeRestockingRulesRepository $freeRestockingRulesRepository;
    private OrderDraftServices $orderDraftServices;


    public function __construct(FreeRestockingRulesRepository $freeRestockingRulesRepository, OrderDraftServices $draftServices)
    {
        $this->freeRestockingRulesRepository = $freeRestockingRulesRepository;
        $this->orderDraftServices = $draftServices;
    }

    public function getFreeRestockingRulesSociety($ordersDraft, $society)
    {
        $rule = $this->freeRestockingRulesRepository->findOneBy(['societyId' => $society]);


        $totalOrder = 0;
        foreach ($ordersDraft as $order) {
            dump($order);
            if (str_contains($rule->getTypeOfRule(), $order->getIdItem()->getIdPresentation()) == true) {
                // TODO :: Je suis éligible à la réduction
                $totalOrder += $order->getPriceOrder();
            }
        }

        dd($totalOrder);

    }

}
