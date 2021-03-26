<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SocietyRepository::class)
 * @UniqueEntity(fields={"idCustomer"}, message="There is already exist")
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

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $idCustomer;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="SocietyID")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="societyID")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function __construct()
    {
        $this->userID = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getIdCustomer(): ?int
    {
        return $this->idCustomer;
    }

    public function setIdCustomer(int $idCustomer): self
    {
        $this->idCustomer = $idCustomer;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setSocietyID($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getSocietyID() === $this) {
                $order->setSocietyID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSocietyID($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSocietyID() === $this) {
                $user->setSocietyID(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


}
