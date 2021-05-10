<?php

namespace App\Entity;

use App\Repository\CustomerIncotermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerIncotermRepository::class)
 */
class CustomerIncoterm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Incoterm::class, inversedBy="customerIncoterms")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="incoterm")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="customerIncoterms")
     */
    private $societyCustomerIncoterm;

    /**
     * @ORM\Column(type="integer")
     */
    private $idModeTransport;



    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?Incoterm
    {
        return $this->reference;
    }

    public function setReference(?Incoterm $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }


    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setIncoterm($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getIncoterm() === $this) {
                $order->setIncoterm(null);
            }
        }

        return $this;
    }

    public function getSocietyCustomerIncoterm(): ?Society
    {
        return $this->societyCustomerIncoterm;
    }

    public function setSocietyCustomerIncoterm(?Society $societyCustomerIncoterm): self
    {
        $this->societyCustomerIncoterm = $societyCustomerIncoterm;

        return $this;
    }

    public function getIdModeTransport(): ?int
    {
        return $this->idModeTransport;
    }

    public function setIdModeTransport(int $idModeTransport): self
    {
        $this->idModeTransport = $idModeTransport;

        return $this;
    }


}
