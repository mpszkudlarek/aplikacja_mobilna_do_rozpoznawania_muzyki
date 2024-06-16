<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public function __construct(string $email, string $password, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->email = $email;
        $this->password = $userPasswordHasher->hashPassword(
            $this,
            $password
        );
        $this->recognitionHistories = new ArrayCollection();
        $this->favouriteTracks = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, RecognitionHistory>
     */
    #[ORM\OneToMany(targetEntity: RecognitionHistory::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $recognitionHistories;

    /**
     * @var Collection<int, FavouriteTrack>
     */
    #[ORM\OneToMany(targetEntity: FavouriteTrack::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $favouriteTracks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, RecognitionHistory>
     */
    public function getRecognitionHistories(): Collection
    {
        return $this->recognitionHistories;
    }

    public function addRecognitionHistory(RecognitionHistory $recognitionHistory): static
    {
        if (!$this->recognitionHistories->contains($recognitionHistory)) {
            $this->recognitionHistories->add($recognitionHistory);
            $recognitionHistory->setUser($this);
        }

        return $this;
    }

    public function removeRecognitionHistory(RecognitionHistory $recognitionHistory): static
    {
        if ($this->recognitionHistories->removeElement($recognitionHistory)) {
            // set the owning side to null (unless already changed)
            if ($recognitionHistory->getUser() === $this) {
                $recognitionHistory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavouriteTrack>
     */
    public function getFavouriteTracks(): Collection
    {
        return $this->favouriteTracks;
    }

    public function addFavouriteTrack(FavouriteTrack $favouriteTrack): static
    {
        if (!$this->favouriteTracks->contains($favouriteTrack)) {
            $this->favouriteTracks->add($favouriteTrack);
            $favouriteTrack->setUser($this);
        }

        return $this;
    }

    public function removeFavouriteTrack(FavouriteTrack $favouriteTrack): static
    {
        if ($this->favouriteTracks->removeElement($favouriteTrack)) {
            // set the owning side to null (unless already changed)
            if ($favouriteTrack->getUser() === $this) {
                $favouriteTrack->setUser(null);
            }
        }

        return $this;
    }
}
