<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;
use App\Enum\ItemType;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $unit_price = null;

    #[ORM\Column(type: "string", enumType: ItemType::class)]
    private ItemType $type;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "items")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: "items")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "items")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Order $order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unit_price !== null ? (float) $this->unit_price : null;
    }

    public function setUnitPrice(float $unit_price): static
    {
        $this->unit_price = (string) $unit_price;
        return $this;
    }

    public function getType(): ItemType
    {
        return $this->type;
    }

    public function setType(ItemType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        if ($cart !== null) {
            $this->order = null; // Un item ne peut pas être dans un panier et une commande en même temps
        }
        $this->cart = $cart;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        if ($order !== null) {
            $this->cart = null; // Un item ne peut pas être dans une commande et un panier en même temps
        }
        $this->order = $order;
        return $this;
    }
}