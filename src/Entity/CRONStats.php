<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CRONStatsRepository;

#[ORM\Entity(repositoryClass: CRONStatsRepository::class)]
class CRONStats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $stat_type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_execution = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $next_execution = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $frequency = null;

    #[ORM\OneToMany(targetEntity: Statistics::class, mappedBy: "cronStats", orphanRemoval: true)]
    private Collection $statistics;

    public function __construct()
    {
        $this->statistics = new ArrayCollection();
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

    public function getLastExecution(): ?\DateTimeInterface
    {
        return $this->last_execution;
    }

    public function setLastExecution(\DateTimeInterface $last_execution): static
    {
        $this->last_execution = $last_execution;
        return $this;
    }

    public function getNextExecution(): ?\DateTimeInterface
    {
        return $this->next_execution;
    }

    public function setNextExecution(\DateTimeInterface $next_execution): static
    {
        $this->next_execution = $next_execution;
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

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): static
    {
        $this->frequency = $frequency;
        return $this;
    }

    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistics $statistic): static
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
            $statistic->setCronStats($this);
        }
        return $this;
    }

    public function removeStatistic(Statistics $statistic): static
    {
        if ($this->statistics->removeElement($statistic)) {
            if ($statistic->getCronStats() === $this) {
                $statistic->setCronStats(null);
            }
        }
        return $this;
    }
}
