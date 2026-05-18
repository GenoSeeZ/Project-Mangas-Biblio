<?php

namespace App\Entity;

use App\Repository\UserMangaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserMangaRepository::class)]
class UserManga
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userMangas')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userMangas')]
    private ?Manga $manga = null;

    #[ORM\Column(length: 255)]
    private ?string $readingStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $addedAt = null;

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

    public function getManga(): ?Manga
    {
        return $this->manga;
    }

    public function setManga(?Manga $manga): static
    {
        $this->manga = $manga;

        return $this;
    }

    public function getReadingStatus(): ?string
    {
        return $this->readingStatus;
    }

    public function setReadingStatus(string $readingStatus): static
    {
        $this->readingStatus = $readingStatus;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }
}
