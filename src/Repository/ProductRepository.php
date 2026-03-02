<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->setParameter('active', true)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findWithFilters(
        ?string $category = null,
        ?string $type = null,
        ?string $base = null,
        ?string $alcoholic = null,
        ?array $excludeAllergens = null,
        ?array $diets = null
    ): array {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->setParameter('active', true);

        if ($category) {
            // Filter by entity type using INSTANCE OF with the actual class
            $class = $this->getClassForCategory($category);
            $qb->andWhere('p INSTANCE OF ' . $class);
        }

        if ($type) {
            $qb->andWhere('p.type = :type')
               ->setParameter('type', $type);
        }

        // Get results first, then filter in PHP for entity-specific fields
        $results = $qb->orderBy('p.name', 'ASC')
                      ->getQuery()
                      ->getResult();

        // Filter by pizza base
        if ($base && $category === 'pizza') {
            $results = array_filter($results, function($p) use ($base) {
                return $p instanceof \App\Entity\Pizza && $p->getBase() === $base;
            });
        }

        // Filter by alcoholic for drinks
        if ($alcoholic !== null && $category === 'boisson') {
            $isAlcoholic = ($alcoholic === 'alcool');
            $results = array_filter($results, function($p) use ($isAlcoholic) {
                return $p instanceof \App\Entity\Drink && $p->isAlcoholic() === $isAlcoholic;
            });
        }

        // Exclude products containing specific allergens
        if ($excludeAllergens && count($excludeAllergens) > 0) {
            $results = array_filter($results, function($p) use ($excludeAllergens) {
                $productAllergens = array_map('strtolower', $p->getAllergens());
                foreach ($excludeAllergens as $allergen) {
                    if (in_array(strtolower($allergen), $productAllergens)) {
                        return false;
                    }
                }
                return true;
            });
        }

        // Filter by dietary preferences
        if ($diets && count($diets) > 0) {
            $dietRules = self::getDietaryRules();
            $results = array_filter($results, function($p) use ($diets, $dietRules) {
                $productType = strtolower($p->getType() ?? '');
                $ingredients = method_exists($p, 'getIngredients') ? $p->getIngredients() : [];
                $ingredientsLower = array_map('strtolower', $ingredients);
                $isAlcoholic = ($p instanceof \App\Entity\Drink && $p->isAlcoholic());

                foreach ($diets as $diet) {
                    if (!isset($dietRules[$diet])) {
                        continue;
                    }
                    $rule = $dietRules[$diet];

                    // Check excluded product types
                    if (isset($rule['excludeTypes']) && in_array($productType, $rule['excludeTypes'])) {
                        return false;
                    }

                    // Check excluded ingredients
                    if (isset($rule['excludeIngredients'])) {
                        foreach ($rule['excludeIngredients'] as $excluded) {
                            foreach ($ingredientsLower as $ingredient) {
                                if (str_contains($ingredient, strtolower($excluded))) {
                                    return false;
                                }
                            }
                        }
                    }

                    // Check if alcohol is excluded
                    if (!empty($rule['excludeAlcohol']) && $isAlcoholic) {
                        return false;
                    }
                }
                return true;
            });
        }

        return array_values($results);
    }

    /**
     * @return Product[]
     */
    public function findPopular(int $limit = 4): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->setParameter('active', true)
            ->orderBy('p.popular', 'DESC')
            ->addOrderBy('p.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique types from products
     * @return string[]
     */
    public function findAllTypes(): array
    {
        $result = $this->createQueryBuilder('p')
            ->select('DISTINCT p.type')
            ->andWhere('p.active = :active')
            ->andWhere('p.type IS NOT NULL')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return array_filter(array_column($result, 'type'));
    }

    /**
     * Get unique types for a specific category
     * @return string[]
     */
    public function findTypesForCategory(string $category): array
    {
        $class = $this->getClassForCategory($category);
        
        $result = $this->createQueryBuilder('p')
            ->select('DISTINCT p.type')
            ->andWhere('p INSTANCE OF ' . $class)
            ->andWhere('p.active = :active')
            ->andWhere('p.type IS NOT NULL')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return array_filter(array_column($result, 'type'));
    }

    /**
     * Get specific filters for a category
     * @return array{filters: array, filterType: string}
     */
    public function getFiltersForCategory(string $category): array
    {
        switch ($category) {
            case 'pizza':
                return [
                    'filterType' => 'base',
                    'filters' => [
                        'tomate' => '🍅 Tomate',
                        'creme' => '🧀 Crème',
                    ],
                ];
            case 'boisson':
                return [
                    'filterType' => 'alcoholic',
                    'filters' => [
                        'soft' => '🥤 Softs',
                        'alcool' => '🍷 Alcools',
                    ],
                ];
            default:
                // For pâtes and desserts, use the type field
                $types = $this->findTypesForCategory($category);
                return [
                    'filterType' => 'type',
                    'filters' => array_combine($types, array_map('ucfirst', $types)),
                ];
        }
    }

    /**
     * Get all categories that have active products
     * @return string[]
     */
    public function findActiveCategories(): array
    {
        $products = $this->findAllActive();
        $categories = [];

        foreach ($products as $product) {
            $cat = $product->getCategory();
            if (!isset($categories[$cat])) {
                $categories[$cat] = true;
            }
        }

        return array_keys($categories);
    }

    /**
     * Group all products by their category
     * @return array<string, Product[]>
     */
    public function findAllGroupedByCategory(): array
    {
        $products = $this->findAllActive();
        
        $grouped = [
            'pizza' => [],
            'pates' => [],
            'dessert' => [],
            'boisson' => [],
        ];

        foreach ($products as $product) {
            $cat = $product->getCategory();
            if (isset($grouped[$cat])) {
                $grouped[$cat][] = $product;
            }
        }

        // Remove empty categories
        return array_filter($grouped, fn($items) => count($items) > 0);
    }

    /**
     * Get all distinct allergens from active products
     * @return array<string, string>
     */
    public function findAllAllergens(): array
    {
        $products = $this->findAllActive();
        return $this->getAllergensFromProducts($products);
    }

    /**
     * Get distinct allergens from a given set of products
     * @param Product[] $products
     * @return array<string, string>
     */
    public function getAllergensFromProducts(array $products): array
    {
        $allergens = [];

        foreach ($products as $product) {
            foreach ($product->getAllergens() as $allergen) {
                $key = strtolower(str_replace([' ', "'"], ['_', '_'], $allergen));
                if (!isset($allergens[$key])) {
                    $allergens[$key] = $allergen;
                }
            }
        }

        ksort($allergens);
        return $allergens;
    }

    /**
     * Get dietary filters that would actually exclude at least one product from the given set
     * @param Product[] $products
     * @return array<string, array{label: string, emoji: string}>
     */
    public function getRelevantDiets(array $products): array
    {
        $allDiets = self::getAvailableDiets();
        $rules = self::getDietaryRules();
        $relevant = [];

        foreach ($allDiets as $dietKey => $diet) {
            if (!isset($rules[$dietKey])) {
                continue;
            }
            $rule = $rules[$dietKey];

            // Check if this diet would exclude at least one product
            foreach ($products as $product) {
                $productType = strtolower($product->getType() ?? '');
                $ingredients = method_exists($product, 'getIngredients') ? $product->getIngredients() : [];
                $ingredientsLower = array_map('strtolower', $ingredients);
                $isAlcoholic = ($product instanceof \App\Entity\Drink && $product->isAlcoholic());

                $excluded = false;

                if (isset($rule['excludeTypes']) && in_array($productType, $rule['excludeTypes'])) {
                    $excluded = true;
                }

                if (!$excluded && isset($rule['excludeIngredients'])) {
                    foreach ($rule['excludeIngredients'] as $excl) {
                        foreach ($ingredientsLower as $ingredient) {
                            if (str_contains($ingredient, strtolower($excl))) {
                                $excluded = true;
                                break 2;
                            }
                        }
                    }
                }

                if (!$excluded && !empty($rule['excludeAlcohol']) && $isAlcoholic) {
                    $excluded = true;
                }

                if ($excluded) {
                    $relevant[$dietKey] = $diet;
                    break; // At least one product excluded, this diet is relevant
                }
            }
        }

        return $relevant;
    }

    /**
     * Get available dietary filters with their rules
     * @return array<string, array{label: string, emoji: string, excludeTypes?: string[], excludeIngredients?: string[], excludeAlcohol?: bool}>
     */
    public static function getAvailableDiets(): array
    {
        return [
            'vegetarien' => [
                'label' => 'Végétarien',
                'emoji' => '🥬',
            ],
            'vegan' => [
                'label' => 'Végan',
                'emoji' => '🌱',
            ],
            'halal' => [
                'label' => 'Halal',
                'emoji' => '🌙',
            ],
            'sans_porc' => [
                'label' => 'Sans porc',
                'emoji' => '🚫🐷',
            ],
        ];
    }

    /**
     * Internal rules for each dietary filter
     */
    private static function getDietaryRules(): array
    {
        return [
            'vegetarien' => [
                'excludeTypes' => ['viande', 'poisson'],
                'excludeIngredients' => ['Bœuf', 'Poulet', 'Bacon', 'Lardons', 'Jambon', 'Pepperoni', 'Saucisse', 'Guanciale', 'Salami', 'Anchois', 'Saumon'],
            ],
            'vegan' => [
                'excludeTypes' => ['viande', 'poisson', 'fromage'],
                'excludeIngredients' => [
                    'Bœuf', 'Poulet', 'Bacon', 'Lardons', 'Jambon', 'Pepperoni', 'Saucisse', 'Guanciale', 'Salami', 'Anchois', 'Saumon',
                    'Mozzarella', 'Parmesan', 'Gorgonzola', 'Chèvre', 'Feta', 'Reblochon', 'Pecorino', 'Fontina', 'Ricotta', 'Mascarpone',
                    'Crème', 'Crème fraîche', 'Beurre', 'Lait', 'Œufs', 'Miel', 'Béchamel',
                ],
            ],
            'halal' => [
                'excludeIngredients' => ['Bacon', 'Lardons', 'Jambon', 'Guanciale', 'Saucisse', 'Pepperoni', 'Salami'],
                'excludeAlcohol' => true,
            ],
            'sans_porc' => [
                'excludeIngredients' => ['Bacon', 'Lardons', 'Jambon', 'Guanciale', 'Saucisse', 'Pepperoni', 'Salami'],
            ],
        ];
    }

    private function getClassForCategory(string $category): string
    {
        return match($category) {
            'pizza' => \App\Entity\Pizza::class,
            'pates' => \App\Entity\Pasta::class,
            'dessert' => \App\Entity\Dessert::class,
            'boisson' => \App\Entity\Drink::class,
            default => Product::class,
        };
    }
}
