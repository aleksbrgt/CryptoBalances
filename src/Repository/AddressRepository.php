<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Repository;

use Aleksbrgt\Balances\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Address find($id, $lockMode = null, $lockVersion = null)
 * @method Address findOneBy(array $criteria, array $orderBy = null)
 * @method Address[] findAll()
 * @method Address[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }
}
