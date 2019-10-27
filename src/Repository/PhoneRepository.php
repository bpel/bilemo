<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function findPhoneById($idPhone)
    {
        return $this->createQueryBuilder('p')
            ->select('p.namePhone, p.colour, p.goStorage, p.price, b.nameBrand')
            ->leftJoin('p.brand','b')
            ->leftJoin('p.osVersion','osv')
            ->andWhere('p.id = :idphone')
            ->setParameter('idphone', $idPhone)
            ->getQuery()
            ->getResult()
            ;
    }
}
