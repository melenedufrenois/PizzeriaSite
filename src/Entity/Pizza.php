<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PizzaRepository::class)]
#[ORM\Table(name: 'pizza')]
class Pizza extends Product
{
    public const BASE_TOMATE = 'tomate';
    public const BASE_CREME = 'creme';

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'La base est obligatoire')]
    #[Assert\Choice(choices: [self::BASE_TOMATE, self::BASE_CREME], message: 'Choisissez une base valide (tomate ou crème)')]
    private ?string $base = null;

    #[ORM\Column(type: Types::JSON)]
    #[Assert\NotNull(message: 'Les ingrédients sont obligatoires')]
    private array $ingredients = [];

    public function getCategory(): string
    {
        return 'pizza';
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(?string $base): static
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
            'Base tomate' => self::BASE_TOMATE,
            'Base crème fraîche' => self::BASE_CREME,
        ];
    }
}
