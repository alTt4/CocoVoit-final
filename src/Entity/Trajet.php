<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrajetRepository::class)
 */
class Trajet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbPlace;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trajets")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="trajets")
     */
    private $villeDepart;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="trajets")
     */
    private $villeArrive;

    /**
     * @ORM\OneToMany(targetEntity=Conditions::class, mappedBy="trajet")
     */
    private $conditions;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Ecoles::class, inversedBy="trajets")
     */
    private $ecole;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $departVille;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $departLat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $departLng;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="trajet")
     */
    private $reservations;

    public function __construct()
    {
        $this->conditions = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(?int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVilleDepart(): ?Ville
    {
        return $this->villeDepart;
    }

    public function setVilleDepart(?Ville $villeDepart): self
    {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    public function getVilleArrive(): ?Ville
    {
        return $this->villeArrive;
    }

    public function setVilleArrive(?Ville $villeArrive): self
    {
        $this->villeArrive = $villeArrive;

        return $this;
    }

    /**
     * @return Collection<int, Conditions>
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    public function addCondition(Conditions $condition): self
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions[] = $condition;
            $condition->setTrajet($this);
        }

        return $this;
    }

    public function removeCondition(Conditions $condition): self
    {
        if ($this->conditions->removeElement($condition)) {
            // set the owning side to null (unless already changed)
            if ($condition->getTrajet() === $this) {
                $condition->setTrajet(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEcole(): ?Ecoles
    {
        return $this->ecole;
    }

    public function setEcole(?Ecoles $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }

    public function getDepartVille(): ?string
    {
        return $this->departVille;
    }

    public function setDepartVille(?string $departVille): self
    {
        $this->departVille = $departVille;

        return $this;
    }

    public function getDepartLat(): ?float
    {
        return $this->departLat;
    }

    public function setDepartLat(?float $departLat): self
    {
        $this->departLat = $departLat;

        return $this;
    }

    public function getDepartLng(): ?float
    {
        return $this->departLng;
    }

    public function setDepartLng(?float $departLng): self
    {
        $this->departLng = $departLng;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setTrajet($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTrajet() === $this) {
                $reservation->setTrajet(null);
            }
        }

        return $this;
    }

}
