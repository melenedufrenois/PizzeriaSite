<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaRepository::class)]
#[ORM\Table(name: 'pizza')]
class Pizza extends Product
{
    public const BASE_TOMATE = 'tomate';
    public const BASE_CREME = 'creme';

    #[ORM\Column(length: 20)]
    private ?string $base = null;

    #[ORM\Column(type: Types::JSON)]
    private array $ingredients = [];

    public function getCategory(): string
    {
        return 'pizza';
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): static
    {
        $this->base = $base;
        return $this;
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

    public static function getAvailableBases(): array
    {
        return [
            self::BASE_TOMATE => 'Base tomate',
            self::BASE_CREME => 'Base crème fraîche',
        ];
    }
}
