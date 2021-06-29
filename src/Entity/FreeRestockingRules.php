<?php

namespace App\Entity;

use App\Repository\FreeRestockingRulesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FreeRestockingRulesRepository::class)
 */
class FreeRestockingRules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Society::class, inversedBy="freeRestockingRules")
     */
    private $societyId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOfRule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valueCondition;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $obtention;

    /**
     * @ORM\Column(type="string")
     */
    private $valueRule;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountStep;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountQuantity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelFr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelEn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocietyId(): ?Society
    {
        return $this->societyId;
    }

    public function setSocietyId(?Society $societyId): self
    {
        $this->societyId = $societyId;

        return $this;
    }

    public function getTypeOfRule(): ?string
    {
        return $this->typeOfRule;
    }

    public function setTypeOfRule(string $typeOfRule): self
    {
        $this->typeOfRule = $typeOfRule;

        return $this;
    }

    public function getValueCondition(): ?string
    {
        return $this->valueCondition;
    }

    public function setValueCondition(string $valueCondition): self
    {
        $this->valueCondition = $valueCondition;

        return $this;
    }

    public function getObtention(): ?string
    {
        return $this->obtention;
    }

    public function setObtention(string $obtention): self
    {
        $this->obtention = $obtention;

        return $this;
    }

    public function getValueRule(): ?int
    {
        return $this->valueRule;
    }

    public function setValueRule(string $valueRule): self
    {
        $this->valueRule = $valueRule;

        return $this;
    }

    public function getAmountStep(): ?int
    {
        return $this->amountStep;
    }

    public function setAmountStep(int $amountStep): self
    {
        $this->amountStep = $amountStep;

        return $this;
    }

    public function getAmountQuantity(): ?int
    {
        return $this->amountQuantity;
    }

    public function setAmountQuantity(int $amountQuantity): self
    {
        $this->amountQuantity = $amountQuantity;

        return $this;
    }

    public function getValidity(): ?bool
    {
        return $this->validity;
    }

    public function setValidity(bool $validity): self
    {
        $this->validity = $validity;

        return $this;
    }

    public function getLabelFr(): ?string
    {
        return $this->labelFr;
    }

    public function setLabelFr(?string $labelFr): self
    {
        $this->labelFr = $labelFr;

        return $this;
    }

    public function getLabelEn(): ?string
    {
        return $this->labelEn;
    }

    public function setLabelEn(?string $labelEn): self
    {
        $this->labelEn = $labelEn;

        return $this;
    }

    public function getLabel($locale): ?string
    {
        if($locale === 'fr-FR') {
            return $this->getLabelFr();
        }
        return $this->getLabelEN();
    }

}
