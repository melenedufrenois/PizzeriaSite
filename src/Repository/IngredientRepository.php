<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * @return Ingredient[] Returns an array of available Ingredient objects
     */
    public function findAllAvailable(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.isAvailable = :available')
            ->setParameter('available', true)
            ->orderBy('i.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Ingredient[] Returns ingredients by name search
     */
    public function findByNameLike(string $name): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('i.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
