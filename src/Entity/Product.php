<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap([
    'pizza' => Pizza::class,
    'pasta' => Pasta::class,
    'dessert' => Dessert::class,
    'drink' => Drink::class,
])]
abstract class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 100)]
    protected ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(length: 255)]
    protected ?string $image = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    protected ?string $price = null;

    #[ORM\Column(length: 50, nullable: true)]
    protected ?string $type = null;

    #[ORM\Column]
    protected bool $popular = false;

    #[ORM\Column]
    protected bool $active = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::JSON)]
    private array $allergens = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function isPopular(): bool
    {
        return $this->popular;
    }

    public function setPopular(bool $popular): static
    {
        $this->popular = $popular;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Returns the category identifier for this product type
     */
    abstract public function getCategory(): string;

    /**
     * Returns the category label for display
     */
    public function getCategoryLabel(): string
    {
        return match($this->getCategory()) {
            'pizza' => 'Pizzas',
            'pates' => 'Pâtes',
            'dessert' => 'Desserts',
            'boisson' => 'Boissons',
            default => ucfirst($this->getCategory()),
        };
    }

    public static function getAvailableCategories(): array
    {
        return [
            'pizza' => 'Pizzas',
            'pates' => 'Pâtes',
            'dessert' => 'Desserts',
            'boisson' => 'Boissons',
        ];
    }

    public function getAllergens(): array
    {
        return isset($this->allergens) ? $this->allergens : [];
    }

    public function setAllergens(array $allergens): static
    {
        $this->allergens = $allergens;

        return $this;
    }
}
