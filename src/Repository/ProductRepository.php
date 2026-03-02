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
        ?array $excludeAllergens = null
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
