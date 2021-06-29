<?php

namespace App\Entity;

use App\Repository\ItemPriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemPriceRepository::class)
 */
class ItemPrice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="itemPrices" )
     */
    private $idItem;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateStartValidity;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEndValidity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricePublic;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceAesthetic;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="itemPrices")
     */
    private $idSociety;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateStartValidity(): ?\DateTimeInterface
    {
        return $this->dateStartValidity;
    }

    public function setDateStartValidity(?\DateTimeInterface $dateStartValidity): self
    {
        $this->dateStartValidity = $dateStartValidity;
        return $this;
    }

    public function getDateEndValidity(): ?\DateTimeInterface
    {
        return $this->dateEndValidity;
    }

    public function setDateEndValidity(?\DateTimeInterface $dateEndValidity): self
    {
        $this->dateEndValidity = $dateEndValidity;
        return $this;
    }

    public function getPricePublic(): ?float
    {
        return $this->pricePublic;
    }

    public function setPricePublic(?float $pricePublic): self
    {
        $this->pricePublic = $pricePublic;
        return $this;
    }

    public function getPriceAesthetic(): ?float
    {
        return $this->priceAesthetic;
    }

    public function setPriceAesthetic(?float $priceAesthetic): self
    {
        $this->priceAesthetic = $priceAesthetic;
        return $this;
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

}
