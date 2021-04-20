<?php


namespace App\Services;


use App\Entity\Order;
use App\Entity\OrderDraft;
use App\Entity\OrderLine;
use App\Repository\OrderDraftRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderDraftServices
{
    private OrderDraftRepository $orderDraftRepository;
    private EntityManagerInterface $em;

    public function __construct(OrderDraftRepository $orderDraftRepository, EntityManagerInterface $em)
    {
        $this->orderDraftRepository = $orderDraftRepository;
        $this->em = $em;
    }

    public function getOrderDraftID($id, $item)
    {
        return $this->orderDraftRepository->findOneBy(['idSociety' => $id, 'idItem' => $item]);
    }

    public function editOrderDraft($id, $society, $orderLine)
    {
;
        foreach ($orderLine as $item) {

            $draft = new OrderDraft();
            $draft->setIdItem($item->getItemId())
                ->setIdSociety($society)
                ->setPrice($item->getPriceUnit())
                ->setQuantity($item->getQuantity())
                ->setQuantityBundling($item->getItemId()->getAmountBulking())
                ->setState(0);

            $this->em->persist($draft);
            $this->em->flush();

        }

        $order = $this->em->getRepository(Order::class)->find($id);
        $this->em->remove($order);
        $this->em->flush();
    }

}
