<?php

namespace App\Entity;

use App\Repository\IntercomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntercomRepository::class)
 */
class Intercom
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
     * @ORM\OneToMany(targetEntity=CustomerIntercom::class, mappedBy="reference")
     */
    private $customerIntercoms;

    public function __construct()
    {
        $this->customerIntercoms = new ArrayCollection();
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
     * @return Collection|CustomerIntercom[]
     */
    public function getCustomerIntercoms(): Collection
    {
        return $this->customerIntercoms;
    }

    public function addCustomerIntercom(CustomerIntercom $customerIntercom): self
    {
        if (!$this->customerIntercoms->contains($customerIntercom)) {
            $this->customerIntercoms[] = $customerIntercom;
            $customerIntercom->setReference($this);
        }

        return $this;
    }

    public function removeCustomerIntercom(CustomerIntercom $customerIntercom): self
    {
        if ($this->customerIntercoms->removeElement($customerIntercom)) {
            // set the owning side to null (unless already changed)
            if ($customerIntercom->getReference() === $this) {
                $customerIntercom->setReference(null);
            }
        }

        return $this;
    }
}
