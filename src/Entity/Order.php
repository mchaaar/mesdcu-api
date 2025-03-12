<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_amount = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: "order", orphanRemoval: true)]
    private Collection $items;

    #[ORM\OneToOne(targetEntity: Payment::class, mappedBy: "order", cascade: ["persist", "remove"])]
    private ?Payment $payment = null;

    #[ORM\OneToMany(targetEntity: OrderHistory::class, mappedBy: "order", orphanRemoval: true)]
    private Collection $histories;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->order_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;
        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount !== null ? (float) $this->total_amount : null;
    }

    public function setTotalAmount(float $total_amount): static
    {
        $this->total_amount = (string) $total_amount;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrder($this);
        }
        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }
        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(OrderHistory $history): static
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setOrder($this);
        }
        return $this;
    }

    public function removeHistory(OrderHistory $history): static
    {
        if ($this->histories->removeElement($history)) {
            if ($history->getOrder() === $this) {
                $history->setOrder(null);
            }
        }
        return $this;
    }
}
