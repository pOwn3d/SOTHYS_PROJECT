<?php

namespace App\Entity;

use App\Repository\GammeProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GammeProductRepository::class)
 * @UniqueEntity(fields={"idCoedi"}, message="There is already exist")
 */
class GammeProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $idCoedi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCoedi(): ?string
    {
        return $this->idCoedi;
    }

    public function setIdCoedi(string $idCoedi): self
    {
        $this->idCoedi = $idCoedi;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName2(): ?string
    {
        return $this->name2;
    }

    public function setName2(?string $name2): self
    {
        $this->name2 = $name2;

        return $this;
    }
}
