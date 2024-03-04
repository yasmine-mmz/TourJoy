<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }
   
    public function findByAccommodationId($refA)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.accommodation = :refA')
        ->setParameter('refA', $refA)
        ->getQuery()
        ->getResult();
}

public function countReservationsByAccommodation()
{
    $qb = $this->createQueryBuilder('c')
        ->select("name.name AS accommodationName, COUNT(c.idR) AS reservationsCount")
        ->leftJoin('c.name', 'name') 
        ->groupBy('accommodationName')
        ->orderBy('accommodationName', 'ASC');

    return $qb->getQuery()->getResult();
}

public function findAllWithUsers(): array
{
    return $this->createQueryBuilder('r')
        ->leftJoin('r.fkuser', 'u')
        ->addSelect('u')
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function countTotalReservations(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.idR)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
