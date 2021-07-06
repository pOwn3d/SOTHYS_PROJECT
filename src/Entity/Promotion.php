<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameFr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameEn;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\ManyToMany(targetEntity=Society::class, inversedBy="promotions")
     */
    private $society;

    /**
     * @ORM\ManyToMany(targetEntity=Plv::class, inversedBy="promotions")
     */
    private $plv;

    /**
     * @ORM\ManyToMany(targetEntity=PromotionItem::class, inversedBy="promotions")
     */
    private $promotionItem;

    /**
     * @ORM\ManyToMany(targetEntity=FreeRules::class, inversedBy="promotions" , fetch="EAGER")
     */
    private $freeRules;

    /**
     * @ORM\OneToMany(targetEntity=OrderDraft::class, mappedBy="promotionId")
     */
    private $orderDrafts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idX3;


    public function __construct()
    {
        $this->society = new ArrayCollection();
        $this->plv = new ArrayCollection();
        $this->promotionItem = new ArrayCollection();
        $this->freeRules = new ArrayCollection();
        $this->orderDrafts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameFr(): ?string
    {
        return $this->nameFr;
    }

    public function setNameFr(?string $nameFr): self
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(?string $nameEn): self
    {
        $this->nameEn = $nameEn;

        return $this;
    }


    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * @return Collection|society[]
     */
    public function getSociety(): Collection
    {
        return $this->society;
    }

    public function addSociety(society $society): self
    {
        if (!$this->society->contains($society)) {
            $this->society[] = $society;
        }

        return $this;
    }

    public function removeSociety(society $society): self
    {
        $this->society->removeElement($society);

        return $this;
    }

    /**
     * @return Collection|Plv[]
     */
    public function getPlv(): Collection
    {
        return $this->plv;
    }

    public function addPlv(Plv $plv): self
    {
        if (!$this->plv->contains($plv)) {
            $this->plv[] = $plv;
        }

        return $this;
    }

    public function removePlv(Plv $plv): self
    {
        $this->plv->removeElement($plv);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotionItem(): Collection
    {
        return $this->promotionItem;
    }

    public function addPromotionItem(PromotionItem $promotionItem): self
    {
        if (!$this->promotionItem->contains($promotionItem)) {
            $this->promotionItem[] = $promotionItem;
        }

        return $this;
    }

    public function removePromotionItem(PromotionItem $promotionItem): self
    {
        $this->promotionItem->removeElement($promotionItem);

        return $this;
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
        }

        return $this;
    }

    public function removeFreeRule(FreeRules $freeRule): self
    {
        $this->freeRules->removeElement($freeRule);

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
            $orderDraft->setPromotionId($this);
        }

        return $this;
    }

    public function removeOrderDraft(OrderDraft $orderDraft): self
    {
        if ($this->orderDrafts->removeElement($orderDraft)) {
            // set the owning side to null (unless already changed)
            if ($orderDraft->getPromotionId() === $this) {
                $orderDraft->setPromotionId(null);
            }
        }

        return $this;
    }

    public function getIdX3(): ?string
    {
        return $this->idX3;
    }

    public function setIdX3(string $idX3): self
    {
        $this->idX3 = $idX3;

        return $this;
    }


}
