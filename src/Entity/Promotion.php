<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelFr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $labelEn;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\OneToMany(targetEntity=Plv::class, mappedBy="promotion")
     */
    private $plvs;

    public function __construct()
    {
        $this->plvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * @return Collection|Plv[]
     */
    public function getPlvs(): Collection
    {
        return $this->plvs;
    }

    public function addPlv(Plv $plv): self
    {
        if (!$this->plvs->contains($plv)) {
            $this->plvs[] = $plv;
            $plv->setPromotion($this);
        }

        return $this;
    }

    public function removePlv(Plv $plv): self
    {
        if ($this->plvs->removeElement($plv)) {
            // set the owning side to null (unless already changed)
            if ($plv->getPromotion() === $this) {
                $plv->setPromotion(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->labelFr;
    }
}
