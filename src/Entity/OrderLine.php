<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=OrderLineRepository::class)
 * @UniqueEntity(fields={"idOrderLine"}, message="Commande déjà enregistré")
 */
class OrderLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOrder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOrderLine;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $priceUnit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remainingQtyOrder;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOrderX3;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="orderLines")
     */
    private $itemID;

    /**
     * @ORM\ManyToOne(targetEntity=OrderDraft::class, inversedBy="orderLines")
     */
    private $orderDraftID;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $promo;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function setIdOrder(?int $idOrder): self
    {
        $this->idOrder = $idOrder;
        return $this;
    }

    public function getIdOrderLine(): ?int
    {
        return $this->idOrderLine;
    }

    public function setIdOrderLine(?int $idOrderLine): self
    {
        $this->idOrderLine = $idOrderLine;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getPriceUnit(): ?int
    {
        return $this->priceUnit;
    }

    public function setPriceUnit(?int $priceUnit): self
    {
        $this->priceUnit = $priceUnit;
        return $this;
    }

    public function getRemainingQtyOrder(): ?int
    {
        return $this->remainingQtyOrder;
    }

    public function setRemainingQtyOrder(?int $remainingQtyOrder): self
    {
        $this->remainingQtyOrder = $remainingQtyOrder;
        return $this;
    }

    public function getIdOrderX3(): ?int
    {
        return $this->idOrderX3;
    }

    public function setIdOrderX3(int $idOrderX3): self
    {
        $this->idOrderX3 = $idOrderX3;
        return $this;
    }

    public function getItemID()
    {
        return $this->itemID;
    }

    public function setItemID($itemID)
    {
        $this->itemID = $itemID;
        return $this;
    }

    public function getOrderDraftID(): ?OrderDraft
    {
        return $this->orderDraftID;
    }

    public function setOrderDraftID(?OrderDraft $orderDraftID): self
    {
        $this->orderDraftID = $orderDraftID;

        return $this;
    }

    public function getPromo(): ?bool
    {
        return $this->promo;
    }

    public function setPromo(?bool $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getDiscount1(): int {
        return 0;
    }

    public function getDiscountedPrice() {
        return $this->getPrice() * (1 - $this->getDiscount1());
    }

    public function getGratuityCode() {
        return '';
    }

    public function getCode() {
        return '';
    }

    public function getIdItem() {
        return $this->getItemID();
    }

    public function quantityBundling() {
        return $this->getItemID()->getAmountBulking();
    }

    public function getIdX3()
    {
        return $this->getIdOrderLine();
    }
}
