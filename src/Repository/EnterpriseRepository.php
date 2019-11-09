<?php

namespace App\Repository;

use App\Entity\Enterprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Enterprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enterprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enterprise[]    findAll()
 * @method Enterprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnterpriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enterprise::class);
    }

    public function findAllEnterprises($page, $limit)
    {
        return $this->createQueryBuilder('e')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getResult()
            ;
    }
}
