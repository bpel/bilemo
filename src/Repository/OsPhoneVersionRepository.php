<?php

namespace App\Repository;

use App\Entity\OsPhoneVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OsPhoneVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method OsPhoneVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method OsPhoneVersion[]    findAll()
 * @method OsPhoneVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OsPhoneVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OsPhoneVersion::class);
    }

    public function findAllOsVersion($page, $limit)
    {
        return $this->createQueryBuilder('osv')
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getResult()
            ;
    }
}
