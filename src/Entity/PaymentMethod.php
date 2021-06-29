<?php

namespace App\Entity;

use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentMethodRepository::class)
 */
class PaymentMethod
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
    private $idX3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $labelFR;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $labelEN;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdX3(): ?string
    {
        return $this->idX3;
    }

    public function setIdX3(string $idX3): self
    {
        $this->idX3 = $idX3;

        return $this;
    }

    public function getLabelFR(): ?string
    {
        return $this->labelFR;
    }

    public function setLabelFR(string $labelFR): self
    {
        $this->labelFR = $labelFR;

        return $this;
    }

    public function getLabelEN(): ?string
    {
        return $this->labelEN;
    }

    public function setLabelEN(string $labelEN): self
    {
        $this->labelEN = $labelEN;

        return $this;
    }

    public function getLabel($locale) {

        if($locale === 'fr-FR') {
            return $this->getlabelFR();
        }

        return $this->getlabelEN();
    }
}
