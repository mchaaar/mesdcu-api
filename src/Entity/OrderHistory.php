<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderHistoryRepository;

#[ORM\Entity(repositoryClass: OrderHistoryRepository::class)]
class OrderHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modification_date = null;

    #[ORM\Column(length: 255)]
    private ?string $previous_status = null;

    #[ORM\Column(length: 255)]
    private ?string $new_status = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "histories")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    public function __construct()
    {
        $this->modification_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modification_date;
    }

    public function setModificationDate(\DateTimeInterface $modification_date): static
    {
        $this->modification_date = $modification_date;
        return $this;
    }

    public function getPreviousStatus(): ?string
    {
        return $this->previous_status;
    }

    public function setPreviousStatus(string $previous_status): static
    {
        $this->previous_status = $previous_status;
        return $this;
    }

    public function getNewStatus(): ?string
    {
        return $this->new_status;
    }

    public function setNewStatus(string $new_status): static
    {
        $this->new_status = $new_status;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;
        return $this;
    }
}
