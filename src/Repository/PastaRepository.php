<?php

namespace App\Repository;

use App\Entity\Pasta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pasta>
 */
class PastaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pasta::class);
    }

    /**
     * @return Pasta[]
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
     * @return Pasta[]
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
     * @return Pasta[]
     */
    public function findWithFilters(?string $type = null, ?string $ingredient = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.active = :active')
            ->setParameter('active', true);

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
     * Get all unique types
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
     * Get all unique ingredients
     * @return string[]
     */
    public function findAllIngredients(): array
    {
        $pastas = $this->findAllActive();
        $ingredients = [];

        foreach ($pastas as $pasta) {
            foreach ($pasta->getIngredients() as $ingredient) {
                $ingredients[$ingredient] = true;
            }
        }

        ksort($ingredients);
        return array_keys($ingredients);
    }
}
