<?php

namespace App\Entity;

use App\Repository\PastaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PastaRepository::class)]
#[ORM\Table(name: 'pasta')]
class Pasta extends Product
{
    #[ORM\Column(type: Types::JSON)]
    private array $ingredients = [];

    #[ORM\Column(length: 50, nullable: true)]
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
