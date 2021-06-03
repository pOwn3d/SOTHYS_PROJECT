<?php

namespace App\Entity;

use App\Repository\FreeRulesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FreeRulesRepository::class)
 */
class FreeRules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="freeRules")
     */
    private $idItemPurchased;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="freeRules")
     */
    private $idItemFree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qtyPurchased;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qtyFree;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPurchasedMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPurchasedMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountFree;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, mappedBy="freeRules")
     */
    private $promotions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $labelFr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $labelEn;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdItemPurchased(): ?item
    {
        return $this->idItemPurchased;
    }

    public function setIdItemPurchased(?item $idItemPurchased): self
    {
        $this->idItemPurchased = $idItemPurchased;

        return $this;
    }

    public function getIdItemFree(): ?item
    {
        return $this->idItemFree;
    }

    public function setIdItemFree(?item $idItemFree): self
    {
        $this->idItemFree = $idItemFree;

        return $this;
    }

    public function getQtyPurchased(): ?int
    {
        return $this->qtyPurchased;
    }

    public function setQtyPurchased(?int $qtyPurchased): self
    {
        $this->qtyPurchased = $qtyPurchased;

        return $this;
    }

    public function getQtyFree(): ?int
    {
        return $this->qtyFree;
    }

    public function setQtyFree(?int $qtyFree): self
    {
        $this->qtyFree = $qtyFree;

        return $this;
    }

    public function getAmountPurchasedMin(): ?int
    {
        return $this->amountPurchasedMin;
    }

    public function setAmountPurchasedMin(?int $amountPurchasedMin): self
    {
        $this->amountPurchasedMin = $amountPurchasedMin;

        return $this;
    }

    public function getAmountPurchasedMax(): ?int
    {
        return $this->amountPurchasedMax;
    }

    public function setAmountPurchasedMax(?int $amountPurchasedMax): self
    {
        $this->amountPurchasedMax = $amountPurchasedMax;

        return $this;
    }

    public function getAmountFree(): ?int
    {
        return $this->amountFree;
    }

    public function setAmountFree(?int $amountFree): self
    {
        $this->amountFree = $amountFree;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->addFreeRule($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeFreeRule($this);
        }

        return $this;
    }

    public function getLabelFr(): ?string
    {
        return $this->labelFr;
    }

    public function setLabelFr(string $labelFr): self
    {
        $this->labelFr = $labelFr;

        return $this;
    }

    public function getLabelEn(): ?string
    {
        return $this->labelEn;
    }

    public function setLabelEn(string $labelEn): self
    {
        $this->labelEn = $labelEn;

        return $this;
    }
    public function __toString(){
        return $this->getLabelFr();
    }
}
