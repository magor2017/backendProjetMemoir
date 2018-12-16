<?php

namespace App\Repository;

use App\Entity\Linkcible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Linkcible|null find($id, $lockMode = null, $lockVersion = null)
 * @method Linkcible|null findOneBy(array $criteria, array $orderBy = null)
 * @method Linkcible[]    findAll()
 * @method Linkcible[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkcibleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Linkcible::class);
    }

//    /**
//     * @return Linkcible[] Returns an array of Linkcible objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Linkcible
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
