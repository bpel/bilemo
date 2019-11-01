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

    // /**
    //  * @return OsPhoneVersion[] Returns an array of OsPhoneVersion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OsPhoneVersion
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
