<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[] Returns orders for a customer
     */
    public function findByCustomer(Customer $customer): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')
            ->addSelect('i')
            ->andWhere('o.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByReference(string $reference): ?Order
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')
            ->addSelect('i')
            ->leftJoin('i.product', 'p')
            ->addSelect('p')
            ->andWhere('o.reference = :reference')
            ->setParameter('reference', $reference)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Order[] Returns orders by status
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')
            ->addSelect('i')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Order[] Returns recent orders
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.items', 'i')
            ->addSelect('i')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
