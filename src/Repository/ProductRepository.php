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
     * @return Product[] Returns all available products with their ingredients
     */
    public function findAllAvailableWithIngredients(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ingredients', 'i')
            ->addSelect('i')
            ->andWhere('p.isAvailable = :available')
            ->setParameter('available', true)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int[] $ingredientIds
     * @return Product[] Returns available pizzas filtered by base and ingredients
     */
    public function findAvailablePizzasWithFilters(?string $baseType, array $ingredientIds = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.ingredients', 'i')
            ->addSelect('i')
            ->andWhere('p.isAvailable = :available')
            ->setParameter('available', true);

        if ($baseType) {
            $qb->andWhere('p.baseType = :baseType')
                ->setParameter('baseType', $baseType);
        }

        if ($ingredientIds) {
            $qb->innerJoin('p.ingredients', 'fi')
                ->andWhere('fi.id IN (:ingredientIds)')
                ->setParameter('ingredientIds', $ingredientIds)
                ->groupBy('p.id')
                ->having('COUNT(DISTINCT fi.id) = :ingredientCount')
                ->setParameter('ingredientCount', count($ingredientIds));
        }

        return $qb->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns popular products
     */
    public function findPopular(int $limit = 4): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ingredients', 'i')
            ->addSelect('i')
            ->andWhere('p.isAvailable = :available')
            ->andWhere('p.isPopular = :popular')
            ->setParameter('available', true)
            ->setParameter('popular', true)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products by category
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ingredients', 'i')
            ->addSelect('i')
            ->andWhere('p.isAvailable = :available')
            ->andWhere('p.category = :category')
            ->setParameter('available', true)
            ->setParameter('category', $category)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products by name search
     */
    public function findByNameLike(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.ingredients', 'i')
            ->addSelect('i')
            ->andWhere('p.name LIKE :name')
            ->andWhere('p.isAvailable = :available')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('available', true)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string[] Returns all unique categories
     */
    public function findAllCategories(): array
    {
        $result = $this->createQueryBuilder('p')
            ->select('DISTINCT p.category')
            ->andWhere('p.category IS NOT NULL')
            ->andWhere('p.isAvailable = :available')
            ->setParameter('available', true)
            ->orderBy('p.category', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'category');
    }
}
