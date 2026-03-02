<?php

namespace App\Entity;

use App\Repository\DessertRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DessertRepository::class)]
#[ORM\Table(name: 'dessert')]
class Dessert extends Product
{
    #[ORM\Column(type: Types::JSON)]
    private array $ingredients = [];

    #[ORM\Column]
    private bool $containsAllergens = false;

    public function getCategory(): string
    {
        return 'dessert';
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

    public function containsAllergens(): bool
    {
        return $this->containsAllergens;
    }

    public function setContainsAllergens(bool $containsAllergens): static
    {
        $this->containsAllergens = $containsAllergens;
        return $this;
    }
}
