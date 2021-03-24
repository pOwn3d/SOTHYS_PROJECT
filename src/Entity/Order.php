<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @UniqueEntity(fields={"idOrderX3"}, message="There is already exist")
 */
class Order
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
    private $idOrder;

    /**
     * 
     * @ORM\Column(type="integer", unique=true)
     */
    private $idOrderX3;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOrder;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDelivery;

    /**
     * @ORM\Column(type="integer")
     */
    private $idStatut;

    /**
     * @ORM\Column(type="integer")
     */
    private $idDownStatut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLastDelivery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function setIdOrder(int $idOrder): self
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getIdOrderX3(): ?int
    {
        return $this->idOrderX3;
    }

    public function setIdOrderX3(int $idOrderX3): self
    {
        $this->idOrderX3 = $idOrderX3;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): self
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getDateDelivery(): ?\DateTimeInterface
    {
        return $this->dateDelivery;
    }

    public function setDateDelivery(\DateTimeInterface $dateDelivery): self
    {
        $this->dateDelivery = $dateDelivery;

        return $this;
    }

    public function getIdStatut(): ?int
    {
        return $this->idStatut;
    }

    public function setIdStatut(int $idStatut): self
    {
        $this->idStatut = $idStatut;

        return $this;
    }

    public function getIdDownStatut(): ?int
    {
        return $this->idDownStatut;
    }

    public function setIdDownStatut(int $idDownStatut): self
    {
        $this->idDownStatut = $idDownStatut;

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

    public function getDateLastDelivery(): ?\DateTimeInterface
    {
        return $this->dateLastDelivery;
    }

    public function setDateLastDelivery(?\DateTimeInterface $dateLastDelivery): self
    {
        $this->dateLastDelivery = $dateLastDelivery;

        return $this;
    }
}
