<?php

namespace App\Repository;

use App\Entity\Likesystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Likesystem>
 *
 * @method Likesystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likesystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likesystem[]    findAll()
 * @method Likesystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likesystem::class);
    }

//    /**
//     * @return Likesystem[] Returns an array of Likesystem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Likesystem
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
