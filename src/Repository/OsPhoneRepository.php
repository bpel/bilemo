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

    // /**
    //  * @return OsPhone[] Returns an array of OsPhone objects
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
    public function findOneBySomeField($value): ?OsPhone
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
