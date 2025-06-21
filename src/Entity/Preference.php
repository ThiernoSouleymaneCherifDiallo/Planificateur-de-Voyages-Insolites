<?php

namespace App\Entity;

use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreferenceRepository::class)]
class Preference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'preferences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $climate = null;

    #[ORM\Column(nullable: true)]
    private ?int $minBudget = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxBudget = null;

    #[ORM\Column(nullable: true)]
    private ?int $minDuration = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxDuration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preferredType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preferredCountry = null;

    #[ORM\Column]
    private ?bool $adventure = false;

    #[ORM\Column]
    private ?bool $culture = false;

    #[ORM\Column]
    private ?bool $relaxation = false;

    #[ORM\Column]
    private ?bool $gastronomy = false;

    #[ORM\Column]
    private ?bool $nature = false;

    #[ORM\Column]
    private ?bool $sport = false;

    #[ORM\Column]
    private ?bool $urban = false;

    #[ORM\Column]
    private ?bool $rural = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClimate(): ?string
    {
        return $this->climate;
    }

    public function setClimate(string $climate): static
    {
        $this->climate = $climate;

        return $this;
    }

    public function getMinBudget(): ?int
    {
        return $this->minBudget;
    }

    public function setMinBudget(int $minBudget): static
    {
        $this->minBudget = $minBudget;

        return $this;
    }

    public function getMaxBudget(): ?int
    {
        return $this->maxBudget;
    }

    public function setMaxBudget(int $maxBudget): static
    {
        $this->maxBudget = $maxBudget;

        return $this;
    }

    public function getMinDuration(): ?int
    {
        return $this->minDuration;
    }

    public function setMinDuration(int $minDuration): static
    {
        $this->minDuration = $minDuration;

        return $this;
    }

    public function getMaxDuration(): ?int
    {
        return $this->maxDuration;
    }

    public function setMaxDuration(int $maxDuration): static
    {
        $this->maxDuration = $maxDuration;

        return $this;
    }

    public function getPreferredType(): ?string
    {
        return $this->preferredType;
    }

    public function setPreferredType(string $preferredType): static
    {
        $this->preferredType = $preferredType;

        return $this;
    }

    public function getPreferredCountry(): ?string
    {
        return $this->preferredCountry;
    }

    public function setPreferredCountry(?string $preferredCountry): static
    {
        $this->preferredCountry = $preferredCountry;

        return $this;
    }

    public function isAdventure(): ?bool
    {
        return $this->adventure;
    }

    public function setAdventure(bool $adventure): static
    {
        $this->adventure = $adventure;

        return $this;
    }

    public function isCulture(): ?bool
    {
        return $this->culture;
    }

    public function setCulture(bool $culture): static
    {
        $this->culture = $culture;

        return $this;
    }

    public function isRelaxation(): ?bool
    {
        return $this->relaxation;
    }

    public function setRelaxation(bool $relaxation): static
    {
        $this->relaxation = $relaxation;

        return $this;
    }

    public function isGastronomy(): ?bool
    {
        return $this->gastronomy;
    }

    public function setGastronomy(bool $gastronomy): static
    {
        $this->gastronomy = $gastronomy;

        return $this;
    }

    public function isNature(): ?bool
    {
        return $this->nature;
    }

    public function setNature(bool $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    public function isSport(): ?bool
    {
        return $this->sport;
    }

    public function setSport(bool $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    public function isUrban(): ?bool
    {
        return $this->urban;
    }

    public function setUrban(bool $urban): static
    {
        $this->urban = $urban;

        return $this;
    }

    public function isRural(): ?bool
    {
        return $this->rural;
    }

    public function setRural(bool $rural): static
    {
        $this->rural = $rural;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getPreferredTypesArray(): array
    {
        $types = [];
        if ($this->adventure) $types[] = 'adventure';
        if ($this->culture) $types[] = 'culture';
        if ($this->relaxation) $types[] = 'relaxation';
        if ($this->gastronomy) $types[] = 'gastronomy';
        if ($this->nature) $types[] = 'nature';
        if ($this->sport) $types[] = 'sport';
        if ($this->urban) $types[] = 'urban';
        if ($this->rural) $types[] = 'rural';
        return $types;
    }
} 