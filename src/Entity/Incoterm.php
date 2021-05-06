<?php

namespace App\Entity;

use App\Repository\IncotermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IncotermRepository::class)
 */
class Incoterm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity=CustomerIncoterm::class, mappedBy="reference")
     */
    private $customerIncoterms;

    public function __construct()
    {
        $this->customerIncoterms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|CustomerIncoterm[]
     */
    public function getCustomerIncoterms(): Collection
    {
        return $this->customerIncoterms;
    }

    public function addCustomerIncoterm(CustomerIncoterm $customerIncoterm): self
    {
        if (!$this->customerIncoterms->contains($customerIncoterm)) {
            $this->customerIncoterms[] = $customerIncoterm;
            $customerIncoterm->setReference($this);
        }

        return $this;
    }

    public function removeCustomerIncoterm(CustomerIncoterm $customerIncoterm): self
    {
        if ($this->customerIncoterms->removeElement($customerIncoterm)) {
            // set the owning side to null (unless already changed)
            if ($customerIncoterm->getReference() === $this) {
                $customerIncoterm->setReference(null);
            }
        }

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->getReference();
    }

}
