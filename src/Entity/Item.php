<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @UniqueEntity(fields={"itemID"}, message="Item déjà présent")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="itemID" , cascade={"remove"})
     */
    private $orderLines;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gammeString;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelFR;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelEN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capacityFR;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capacityEN;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $idPresentation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usageString;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountBulking;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeEAN;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idAtTheRate;

    /**
     * @ORM\ManyToOne(targetEntity=GammeProduct::class, inversedBy="items")
     */
    private $gamme;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $itemID;

    /**
     * @ORM\OneToMany(targetEntity=ItemPrice::class, mappedBy="idItem")
     */
    private $itemPrices;

    /**
     * @ORM\OneToMany(targetEntity=ItemQuantity::class, mappedBy="idItem", cascade={"remove"} )
     */
    private $itemQuantities;

    /**
     * @ORM\OneToMany(targetEntity=OrderDraft::class, mappedBy="idItem")
     */
    private $orderDrafts;

    /**
     * @ORM\OneToMany(targetEntity=PromotionItem::class, mappedBy="item")
     */
    private $promotionItems;

    /**
     * @ORM\OneToMany(targetEntity=FreeRules::class, mappedBy="idItemPurchased")
     */
    private $freeRules;

    /**
     * @ORM\ManyToOne(targetEntity=GenericName::class, inversedBy="items")
     */
    private $genericName;


    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
        $this->itemPrices = new ArrayCollection();
        $this->itemQuantities = new ArrayCollection();
        $this->orderDrafts = new ArrayCollection();
        $this->promotionItems = new ArrayCollection();
        $this->freeRules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setItemID($this);
        }
        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getItemID() === $this) {
                $orderLine->setItemID(null);
            }
        }
        return $this;
    }

    public function getGammeString(): ?string
    {
        return $this->gammeString;
    }

    public function setGammeString(string $gammeString): self
    {
        $this->gammeString = $gammeString;
        return $this;
    }


    public function getLabelFR(): ?string
    {
        return $this->labelFR;
    }

    public function setLabelFR(?string $labelFR): self
    {
        $this->labelFR = $labelFR;
        return $this;
    }

    public function getLabelEN(): ?string
    {
        return $this->labelEN;
    }

    public function setLabelEN(?string $labelEN): self
    {
        $this->labelEN = $labelEN;
        return $this;
    }

    public function getCapacityFR(): ?string
    {
        return $this->capacityFR;
    }

    public function setCapacityFR(?string $capacityFR): self
    {
        $this->capacityFR = $capacityFR;
        return $this;
    }

    public function getCapacityEN(): ?string
    {
        return $this->capacityEN;
    }

    public function setCapacityEN(?string $capacityEN): self
    {
        $this->capacityEN = $capacityEN;
        return $this;
    }

    public function getIdPresentation(): ?string
    {
        return $this->idPresentation;
    }

    public function setIdPresentation(?string $idPresentation): self
    {
        $this->idPresentation = $idPresentation;
        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;
        return $this;
    }

    public function getUsageString(): ?string
    {
        return $this->usageString;
    }

    public function setUsageString(?string $usageString): self
    {
        $this->usageString = $usageString;
        return $this;
    }

    public function getAmountBulking(): ?int
    {
        return $this->amountBulking;
    }

    public function setAmountBulking(?int $amountBulking): self
    {
        $this->amountBulking = $amountBulking;
        return $this;
    }

    public function getCodeEAN(): ?string
    {
        return $this->codeEAN;
    }

    public function setCodeEAN(?string $codeEAN): self
    {
        $this->codeEAN = $codeEAN;
        return $this;
    }

    public function getIdAtTheRate(): ?int
    {
        return $this->idAtTheRate;
    }

    public function setIdAtTheRate(?int $idAtTheRate): self
    {
        $this->idAtTheRate = $idAtTheRate;
        return $this;
    }

    public function getGamme(): ?GammeProduct
    {
        return $this->gamme;
    }

    public function setGamme(?GammeProduct $gamme): self
    {
        $this->gamme = $gamme;
        return $this;
    }

    public function getItemID(): ?string
    {
        return $this->itemID;
    }

    public function setItemID(string $itemID): self
    {
        $this->itemID = $itemID;
        return $this;
    }

    /**
     * @return Collection|ItemPrice[]
     */
    public function getItemPrices(): Collection
    {
        return $this->itemPrices;
    }

    public function addItemPrice(ItemPrice $itemPrice): self
    {
        if (!$this->itemPrices->contains($itemPrice)) {
            $this->itemPrices[] = $itemPrice;
            $itemPrice->setIdItem($this);
        }
        return $this;
    }

    public function removeItemPrice(ItemPrice $itemPrice): self
    {
        if ($this->itemPrices->removeElement($itemPrice)) {
            // set the owning side to null (unless already changed)
            if ($itemPrice->getIdItem() === $this) {
                $itemPrice->setIdItem(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|ItemQuantity[]
     */
    public function getItemQuantities(): Collection
    {
        return $this->itemQuantities;
    }

    public function addItemQuantity(ItemQuantity $itemQuantity): self
    {
        if (!$this->itemQuantities->contains($itemQuantity)) {
            $this->itemQuantities[] = $itemQuantity;
            $itemQuantity->setIdItem($this);
        }
        return $this;
    }

    public function removeItemQuantity(ItemQuantity $itemQuantity): self
    {
        if ($this->itemQuantities->removeElement($itemQuantity)) {
            // set the owning side to null (unless already changed)
            if ($itemQuantity->getIdItem() === $this) {
                $itemQuantity->setIdItem(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|OrderDraft[]
     */
    public function getOrderDrafts(): Collection
    {
        return $this->orderDrafts;
    }

    public function addOrderDraft(OrderDraft $orderDraft): self
    {
        if (!$this->orderDrafts->contains($orderDraft)) {
            $this->orderDrafts[] = $orderDraft;
            $orderDraft->setIdItem($this);
        }
        return $this;
    }

    public function removeOrderDraft(OrderDraft $orderDraft): self
    {
        if ($this->orderDrafts->removeElement($orderDraft)) {
            // set the owning side to null (unless already changed)
            if ($orderDraft->getIdItem() === $this) {
                $orderDraft->setIdItem(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|PromotionItem[]
     */
    public function getPromotionItems(): Collection
    {
        return $this->promotionItems;
    }

    public function addPromotionItem(PromotionItem $promotionItem): self
    {
        if (!$this->promotionItems->contains($promotionItem)) {
            $this->promotionItems[] = $promotionItem;
            $promotionItem->setItem($this);
        }

        return $this;
    }

    public function removePromotionItem(PromotionItem $promotionItem): self
    {
        if ($this->promotionItems->removeElement($promotionItem)) {
            // set the owning side to null (unless already changed)
            if ($promotionItem->getItem() === $this) {
                $promotionItem->setItem(null);
            }
        }

        return $this;
    }

    public function getLabel($locale) {

        if($locale === 'fr-FR') {
            return $this->getLabelFR();
        }

        return $this->getLabelEN();
    }

    public function getCapacity($locale) {

        if($locale === 'fr-FR') {
            return $this->capacityFR;
        }

        return $this->capacityEN;
    }

    public function __toString(): string
    {
        return $this->getLabelEN();
    }

    /**
     * @return Collection|FreeRules[]
     */
    public function getFreeRules(): Collection
    {
        return $this->freeRules;
    }

    public function addFreeRule(FreeRules $freeRule): self
    {
        if (!$this->freeRules->contains($freeRule)) {
            $this->freeRules[] = $freeRule;
            $freeRule->setIdItemPurchased($this);
        }

        return $this;
    }

    public function removeFreeRule(FreeRules $freeRule): self
    {
        if ($this->freeRules->removeElement($freeRule)) {
            // set the owning side to null (unless already changed)
            if ($freeRule->getIdItemPurchased() === $this) {
                $freeRule->setIdItemPurchased(null);
            }
        }

        return $this;
    }

    public function getGenericName(): ?GenericName
    {
        return $this->genericName;
    }

    public function setGenericName(?GenericName $genericName): self
    {
        $this->genericName = $genericName;

        return $this;
    }
}
