<?php

namespace App\Entity;

use App\Repository\PlanificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanificationRepository::class)]
class Planification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Destination $destination = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'La date de départ est obligatoire')]
    #[Assert\GreaterThan('today', message: 'La date de départ doit être dans le futur')]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'La date de retour est obligatoire')]
    #[Assert\GreaterThan(propertyPath: 'dateDepart', message: 'La date de retour doit être après la date de départ')]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = 'planifie';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $budgetEstime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accompagnants = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $activites = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hebergement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transport = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->statut = 'planifie';
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

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): static
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): static
    {
        $this->dateRetour = $dateRetour;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getBudgetEstime(): ?string
    {
        return $this->budgetEstime;
    }

    public function setBudgetEstime(?string $budgetEstime): static
    {
        $this->budgetEstime = $budgetEstime;
        return $this;
    }

    public function getAccompagnants(): ?string
    {
        return $this->accompagnants;
    }

    public function setAccompagnants(?string $accompagnants): static
    {
        $this->accompagnants = $accompagnants;
        return $this;
    }

    public function getActivites(): ?string
    {
        return $this->activites;
    }

    public function setActivites(?string $activites): static
    {
        $this->activites = $activites;
        return $this;
    }

    public function getHebergement(): ?string
    {
        return $this->hebergement;
    }

    public function setHebergement(?string $hebergement): static
    {
        $this->hebergement = $hebergement;
        return $this;
    }

    public function getTransport(): ?string
    {
        return $this->transport;
    }

    public function setTransport(?string $transport): static
    {
        $this->transport = $transport;
        return $this;
    }

    // Méthodes utilitaires
    public function getDureeSejour(): int
    {
        if ($this->dateDepart && $this->dateRetour) {
            return $this->dateDepart->diff($this->dateRetour)->days;
        }
        return 0;
    }

    public function getStatutLabel(): string
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    public function getStatutClass(): string
    {
        return match($this->statut) {
            'planifie' => 'primary',
            'en_cours' => 'warning',
            'termine' => 'success',
            'annule' => 'danger',
            default => 'secondary'
        };
    }

    public function isEnCours(): bool
    {
        $now = new \DateTime();
        return $this->dateDepart <= $now && $this->dateRetour >= $now;
    }

    public function isTermine(): bool
    {
        $now = new \DateTime();
        return $this->dateRetour < $now;
    }

    public function isAVenir(): bool
    {
        $now = new \DateTime();
        return $this->dateDepart > $now;
    }
} 