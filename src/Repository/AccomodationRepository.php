<?php

namespace App\Repository;

use App\Entity\Accomodation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accomodation>
 *
 * @method Accomodation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accomodation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accomodation[]    findAll()
 * @method Accomodation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccomodationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accomodation::class);
    }

 public function findByType(string $type): array
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.type = :type')
        ->setParameter('type', $type)
        ->getQuery()
        ->getResult();
}

    
public function countTotalAccommodations(): int
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.refA)') // Change 'id' to 'refA' or whatever your primary key field is named
        ->getQuery()
        ->getSingleScalarResult();
}
   


//    /**
//     * @return Accomodation[] Returns an array of Accomodation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Accomodation
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




}
