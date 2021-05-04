<?php

namespace App\Entity;

use App\Repository\CustomerIntercomRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerIntercomRepository::class)
 */
class CustomerIntercom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=intercom::class, inversedBy="customerIntercoms")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="customerIntercoms")
     */
    private $intercomSociety;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?intercom
    {
        return $this->reference;
    }

    public function setReference(?intercom $reference): self
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

    public function getIntercomSociety(): ?Society
    {
        return $this->intercomSociety;
    }

    public function setIntercomSociety(?Society $intercomSociety): self
    {
        $this->intercomSociety = $intercomSociety;

        return $this;
    }
}
