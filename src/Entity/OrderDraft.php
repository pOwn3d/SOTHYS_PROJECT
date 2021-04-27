<?php

namespace App\Entity;

use App\Repository\OrderDraftRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDraftRepository::class)
 */
class OrderDraft
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="orderDrafts")
     */
    private $idSociety;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="orderDrafts", fetch="EAGER")
     */
    private $idItem;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityBundling;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSociety(): ?Society
    {
        return $this->idSociety;
    }

    public function setIdSociety(?Society $idSociety): self
    {
        $this->idSociety = $idSociety;
        return $this;
    }

    public function getIdItem(): ?Item
    {
        return $this->idItem;
    }

    public function setIdItem(?Item $idItem): self
    {
        $this->idItem = $idItem;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(?bool $state): self
    {
        $this->state = $state;
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

    public function getQuantityBundling(): ?int
    {
        return $this->quantityBundling;
    }

    public function setQuantityBundling(int $quantityBundling): self
    {
        $this->quantityBundling = $quantityBundling;

        return $this;
    }

    public function getPriceOrder(): ?float
    {
        return $this->priceOrder;
    }

    public function setPriceOrder(?float $priceOrder): self
    {
        $this->priceOrder = $priceOrder;

        return $this;
    }

}
