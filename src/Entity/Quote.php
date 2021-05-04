<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 */
class Quote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=society::class, inversedBy="quotes")
     */
    private $society;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateQuote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSociety(): ?society
    {
        return $this->society;
    }

    public function setSociety(?society $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getDateQuote(): ?\DateTimeInterface
    {
        return $this->dateQuote;
    }

    public function setDateQuote(\DateTimeInterface $dateQuote): self
    {
        $this->dateQuote = $dateQuote;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
