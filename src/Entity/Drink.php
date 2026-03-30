<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
#[ORM\Table(name: 'drink')]
class Drink extends Product
{
    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: 'Le volume ne peut pas dépasser {{ limit }} caractères')]
    private ?string $volume = null;

    #[ORM\Column]
    private bool $isAlcoholic = false;

    public function getCategory(): string
    {
        return 'boisson';
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): static
    {
        $this->volume = $volume;
        return $this;
    }

    public function isAlcoholic(): bool
    {
        return $this->isAlcoholic;
    }

    public function setIsAlcoholic(bool $isAlcoholic): static
    {
        $this->isAlcoholic = $isAlcoholic;
        return $this;
    }
}
