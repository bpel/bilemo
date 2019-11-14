<?php

namespace App\Repository;

use App\Entity\OsPhone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OsPhone|null find($id, $lockMode = null, $lockVersion = null)
 * @method OsPhone|null findOneBy(array $criteria, array $orderBy = null)
 * @method OsPhone[]    findAll()
 * @method OsPhone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OsPhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OsPhone::class);
    }

    public function findAllOsPhones($page, $limit)
    {
        return $this->createQueryBuilder('op')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getResult()
            ;
    }
}
