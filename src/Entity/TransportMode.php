<?php

namespace App\Entity;

use App\Repository\TransportModeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransportModeRepository::class)
 */
class TransportMode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idTransport;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameFR;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameEN;

    /**
     * @ORM\OneToMany(targetEntity=CustomerIncoterm::class, mappedBy="modeTransport")
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

    public function getIdTransport(): ?int
    {
        return $this->idTransport;
    }

    public function setIdTransport(int $idTransport): self
    {
        $this->idTransport = $idTransport;

        return $this;
    }

    public function getNameFR(): ?string
    {
        return $this->nameFR;
    }

    public function setNameFR(?string $nameFR): self
    {
        $this->nameFR = $nameFR;

        return $this;
    }

    public function getNameEN(): ?string
    {
        return $this->nameEN;
    }

    public function setNameEN(?string $nameEN): self
    {
        $this->nameEN = $nameEN;

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
            $customerIncoterm->setModeTransport($this);
        }

        return $this;
    }

    public function removeCustomerIncoterm(CustomerIncoterm $customerIncoterm): self
    {
        if ($this->customerIncoterms->removeElement($customerIncoterm)) {
            // set the owning side to null (unless already changed)
            if ($customerIncoterm->getModeTransport() === $this) {
                $customerIncoterm->setModeTransport(null);
            }
        }

        return $this;
    }

    public function getName($locale): ?string
    {
        if($locale === 'fr-FR') {
            return $this->nameFR;
        }
        return $this->nameEN;
    }

    public function __toString()
    {
        return $this->nameFR . '  -  ' . $this->nameEN;
    }
}
