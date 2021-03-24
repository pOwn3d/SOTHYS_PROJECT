<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocietyRepository::class)
 */
class Society
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="society")
     */
    private $userID;

    public function __construct()
    {
        $this->userID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserID(): Collection
    {
        return $this->userID;
    }

    public function addUserID(User $userID): self
    {
        if (!$this->userID->contains($userID)) {
            $this->userID[] = $userID;
            $userID->setSociety($this);
        }

        return $this;
    }

    public function removeUserID(User $userID): self
    {
        if ($this->userID->removeElement($userID)) {
            // set the owning side to null (unless already changed)
            if ($userID->getSociety() === $this) {
                $userID->setSociety(null);
            }
        }

        return $this;
    }
}
