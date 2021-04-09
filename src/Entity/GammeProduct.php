<?php

namespace App\Entity;

use App\Repository\GammeProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GammeProductRepository::class)
 * @UniqueEntity(fields={"refID"}, message="There is already exist")
 */
class GammeProduct
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
    private $labelFR;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelEN;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="gamme")
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     */
    private $refID;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Product[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Product $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setGamme($this);
        }

        return $this;
    }

    public function removeItem(Product $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getGamme() === $this) {
                $item->setGamme(null);
            }
        }

        return $this;
    }

    public function getRefID(): ?string
    {
        return $this->refID;
    }

    public function setRefID(string $refID): self
    {
        $this->refID = $refID;

        return $this;
    }
}
