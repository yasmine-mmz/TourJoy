<?php

namespace App\Repository;

use App\Entity\Claims;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Claims>
 *
 * @method Claims|null find($id, $lockMode = null, $lockVersion = null)
 * @method Claims|null findOneBy(array $criteria, array $orderBy = null)
 * @method Claims[]    findAll()
 * @method Claims[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClaimsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Claims::class);
    }

//    /**
//     * @return Claims[] Returns an array of Claims objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Claims
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
