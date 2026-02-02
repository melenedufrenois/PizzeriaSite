<?php

namespace App\Repository;

use App\Entity\Pizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pizza>
 */
class PizzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pizza::class);
    }

    /**
     * @return Pizza[]
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
     * @return Pizza[]
     */
    public function findByBase(string $base): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->andWhere('p.base = :base')
            ->setParameter('active', true)
            ->setParameter('base', $base)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Pizza[]
     */
    public function findGroupedByBase(): array
    {
        $pizzas = $this->findAllActive();
        
        $grouped = [
            Pizza::BASE_TOMATE => [],
            Pizza::BASE_CREME => [],
        ];

        foreach ($pizzas as $pizza) {
            $base = $pizza->getBase();
            if (isset($grouped[$base])) {
                $grouped[$base][] = $pizza;
            }
        }

        return $grouped;
    }

    /**
     * @return Pizza[]
     */
    public function findWithFilters(?string $base = null, ?string $type = null, ?string $ingredient = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->setParameter('active', true);

        if ($base) {
            $qb->andWhere('p.base = :base')
               ->setParameter('base', $base);
        }

        if ($type) {
            $qb->andWhere('p.type = :type')
               ->setParameter('type', $type);
        }

        if ($ingredient) {
            $qb->andWhere('p.ingredients LIKE :ingredient')
               ->setParameter('ingredient', '%' . $ingredient . '%');
        }

        return $qb->orderBy('p.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * @return Pizza[]
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
     * Get all unique types from pizzas
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
     * Get all unique ingredients from all pizzas
     * @return string[]
     */
    public function findAllIngredients(): array
    {
        $pizzas = $this->findAllActive();
        $ingredients = [];

        foreach ($pizzas as $pizza) {
            foreach ($pizza->getIngredients() as $ingredient) {
                $ingredients[$ingredient] = true;
            }
        }

        return array_keys($ingredients);
    }
}
