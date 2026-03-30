<?php

namespace App\Entity;

use App\Repository\PastaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PastaRepository::class)]
#[ORM\Table(name: 'pasta')]
class Pasta extends Product
{
    #[ORM\Column(type: Types::JSON)]
    #[Assert\NotNull(message: 'Les ingrédients sont obligatoires')]
    private array $ingredients = [];

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: 'Le type de pâte ne peut pas dépasser {{ limit }} caractères')]
    private ?string $pastaType = null;

    public function getCategory(): string
    {
        return 'pates';
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): static
    {
        $this->ingredients = $ingredients;
        return $this;
    }

    public function getPastaType(): ?string
    {
        return $this->pastaType;
    }

    public function setPastaType(?string $pastaType): static
    {
        $this->pastaType = $pastaType;
        return $this;
    }
}
