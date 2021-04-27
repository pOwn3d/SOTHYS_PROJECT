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

    /**
     * @ORM\OneToMany(targetEntity=ItemPrice::class, mappedBy="idSociety")
     */
    private $itemPrices;

    /**
     * @ORM\OneToMany(targetEntity=ItemQuantity::class, mappedBy="IdSociety")
     */
    private $itemQuantities;

    /**
     * @ORM\OneToMany(targetEntity=OrderDraft::class, mappedBy="idSociety")
     */
    private $orderDrafts;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->users  = new ArrayCollection();
        $this->itemPrices = new ArrayCollection();
        $this->itemQuantities = new ArrayCollection();
        $this->orderDrafts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|ItemPrice[]
     */
    public function getItemPrices(): Collection
    {
        return $this->itemPrices;
    }

    public function addItemPrice(ItemPrice $itemPrice): self
    {
        if (!$this->itemPrices->contains($itemPrice)) {
            $this->itemPrices[] = $itemPrice;
            $itemPrice->setIdSociety($this);
        }
        return $this;
    }

    public function removeItemPrice(ItemPrice $itemPrice): self
    {
        if ($this->itemPrices->removeElement($itemPrice)) {
            // set the owning side to null (unless already changed)
            if ($itemPrice->getIdSociety() === $this) {
                $itemPrice->setIdSociety(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|ItemQuantity[]
     */
    public function getItemQuantities(): Collection
    {
        return $this->itemQuantities;
    }

    public function addItemQuantity(ItemQuantity $itemQuantity): self
    {
        if (!$this->itemQuantities->contains($itemQuantity)) {
            $this->itemQuantities[] = $itemQuantity;
            $itemQuantity->setIdSociety($this);
        }
        return $this;
    }

    public function removeItemQuantity(ItemQuantity $itemQuantity): self
    {
        if ($this->itemQuantities->removeElement($itemQuantity)) {
            // set the owning side to null (unless already changed)
            if ($itemQuantity->getIdSociety() === $this) {
                $itemQuantity->setIdSociety(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|OrderDraft[]
     */
    public function getOrderDrafts(): Collection
    {
        return $this->orderDrafts;
    }

    public function addOrderDraft(OrderDraft $orderDraft): self
    {
        if (!$this->orderDrafts->contains($orderDraft)) {
            $this->orderDrafts[] = $orderDraft;
            $orderDraft->setIdSociety($this);
        }
        return $this;
    }

    public function removeOrderDraft(OrderDraft $orderDraft): self
    {
        if ($this->orderDrafts->removeElement($orderDraft)) {
            // set the owning side to null (unless already changed)
            if ($orderDraft->getIdSociety() === $this) {
                $orderDraft->setIdSociety(null);
            }
        }
        return $this;
    }

}
