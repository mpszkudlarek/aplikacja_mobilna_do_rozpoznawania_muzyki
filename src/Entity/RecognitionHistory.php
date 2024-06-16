<?php

namespace App\Entity;

use App\Repository\RecognitionHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecognitionHistoryRepository::class)]
class RecognitionHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $track_info = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $recognition_date = null;

    #[ORM\ManyToOne(inversedBy: 'recognitionHistories')]
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

    public function getRecognitionDate(): ?\DateTimeInterface
    {
        return $this->recognition_date;
    }

    public function setRecognitionDate(\DateTimeInterface $recognition_date): static
    {
        $this->recognition_date = $recognition_date;

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
