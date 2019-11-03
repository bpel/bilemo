<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUsersByEnterprise($idEnterprise)
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->leftJoin('u.enterprise','e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $idEnterprise)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findUserById($idUser)
    {
        return $this->createQueryBuilder('u')
            ->select()
            ->leftJoin('u.enterprise','e')
            ->andWhere('u.id = :iduser')
            ->setParameter('iduser', $idUser)
            ->getQuery()
            ->getResult()
            ;
    }
}
