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
     * @ORM\Column(type="integer")
     */
    private $idOrderX3;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="orderLines", fetch="EAGER")
     */
    private $itemID;


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

    public function getItemID(): ?Product
    {
        return $this->itemID;
    }

    public function setItemID(?Product $itemID): self
    {
        $this->itemID = $itemID;

        return $this;
    }

}