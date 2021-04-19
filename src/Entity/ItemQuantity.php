<?php

namespace App\Entity;

use App\Repository\ItemQuantityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemQuantityRepository::class)
 */
class ItemQuantity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="itemQuantities")
     */
    private $IdSociety;

    /**
     * @ORM\ManyToOne(targetEntity=item::class, inversedBy="itemQuantities")
     */
    private $idItem;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSociety(): ?Society
    {
        return $this->IdSociety;
    }

    public function setIdSociety(?Society $IdSociety): self
    {
        $this->IdSociety = $IdSociety;

        return $this;
    }

    public function getIdItem(): ?item
    {
        return $this->idItem;
    }

    public function setIdItem(?item $idItem): self
    {
        $this->idItem = $idItem;

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
}
