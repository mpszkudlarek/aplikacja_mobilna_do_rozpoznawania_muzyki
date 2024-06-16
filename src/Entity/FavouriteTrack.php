<?php

namespace App\Entity;

use App\Repository\FavouriteTrackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavouriteTrackRepository::class)]
class FavouriteTrack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $track_info = [];

    #[ORM\ManyToOne(inversedBy: 'favouriteTracks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackInfo(): array
    {
        return $this->track_info;
    }

    public function setTrackInfo(array $track_info): static
    {
        $this->track_info = $track_info;

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
}
