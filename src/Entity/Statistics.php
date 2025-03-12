<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StatisticsRepository;

#[ORM\Entity(repositoryClass: StatisticsRepository::class)]
class Statistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $stat_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $calculated_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $value = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: CRONStats::class, inversedBy: "statistics")]
    #[ORM\JoinColumn(nullable: false)]
    private ?CRONStats $cronStats = null;

    public function __construct()
    {
        $this->calculated_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatType(): ?string
    {
        return $this->stat_type;
    }

    public function setStatType(string $stat_type): static
    {
        $this->stat_type = $stat_type;
        return $this;
    }

    public function getCalculatedAt(): ?\DateTimeInterface
    {
        return $this->calculated_at;
    }

    public function setCalculatedAt(\DateTimeInterface $calculated_at): static
    {
        $this->calculated_at = $calculated_at;
        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value !== null ? (float) $this->value : null;
    }

    public function setValue(float $value): static
    {
        $this->value = (string) $value;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCronStats(): ?CRONStats
    {
        return $this->cronStats;
    }

    public function setCronStats(?CRONStats $cronStats): static
    {
        $this->cronStats = $cronStats;
        return $this;
    }
}