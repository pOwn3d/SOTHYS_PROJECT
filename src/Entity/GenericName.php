<?php

namespace App\Entity;

use App\Repository\GenericNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenericNameRepository::class)
 */
class GenericName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="genericName")
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameFR;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameEN;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $itemId;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setGenericName($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getGenericName() === $this) {
                $item->setGenericName(null);
            }
        }

        return $this;
    }

    public function getNameFR(): ?string
    {
        return $this->nameFR;
    }

    public function setNameFR(string $nameFR): self
    {
        $this->nameFR = $nameFR;

        return $this;
    }

    public function getNameEN(): ?string
    {
        return $this->nameEN;
    }

    public function setNameEN(string $nameEN): self
    {
        $this->nameEN = $nameEN;

        return $this;
    }

    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getName($locale) {

        if($locale === 'fr-FR') {
            return $this->getNameFR();
        }

        return $this->getNameEN();
    }

    public function getUniqueId()
    {
        return $this->getItemId();
    }
}
