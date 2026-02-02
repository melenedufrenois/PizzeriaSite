<?php

namespace App\Repository;

use App\Entity\Drink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Drink>
 */
class DrinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drink::class);
    }

    /**
     * @return Drink[]
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.active = :active')
            ->setParameter('active', true)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Drink[]
     */
    public function findPopular(int $limit = 4): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.active = :active')
            ->setParameter('active', true)
            ->orderBy('d.popular', 'DESC')
            ->addOrderBy('d.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Drink[]
     */
    public function findWithFilters(?string $type = null, ?bool $alcoholic = null): array
    {
        $qb = $this->createQueryBuilder('d')
            ->andWhere('d.active = :active')
            ->setParameter('active', true);

        if ($type) {
            $qb->andWhere('d.type = :type')
               ->setParameter('type', $type);
        }

        if ($alcoholic !== null) {
            $qb->andWhere('d.isAlcoholic = :alcoholic')
               ->setParameter('alcoholic', $alcoholic);
        }

        return $qb->orderBy('d.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * @return Drink[]
     */
    public function findNonAlcoholic(): array
    {
        return $this->findWithFilters(null, false);
    }

    /**
     * Get all unique types
     * @return string[]
     */
    public function findAllTypes(): array
    {
        $result = $this->createQueryBuilder('d')
            ->select('DISTINCT d.type')
            ->andWhere('d.active = :active')
            ->andWhere('d.type IS NOT NULL')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return array_filter(array_column($result, 'type'));
    }
}
