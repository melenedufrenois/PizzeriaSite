<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findByCustomer(Customer $customer): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.items', 'i')
            ->addSelect('i')
            ->leftJoin('i.product', 'p')
            ->addSelect('p')
            ->andWhere('c.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findBySessionId(string $sessionId): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.items', 'i')
            ->addSelect('i')
            ->leftJoin('i.product', 'p')
            ->addSelect('p')
            ->andWhere('c.sessionId = :sessionId')
            ->setParameter('sessionId', $sessionId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
