<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function countBookingsByGuide()
{
    $qb = $this->createQueryBuilder('b')
        ->select("g.firstnameG AS guideFirstname, g.lastnameG AS guideLastname, COUNT(b.id) AS bookingsCount")
        ->leftJoin('b.guide_id', 'g') // Assuming 'guide_id' is the property in the booking entity that references the guide entity
        ->groupBy('guideFirstname, guideLastname')
        ->orderBy('guideFirstname', 'ASC');

    return $qb->getQuery()->getResult();
}

public function bookingsPerMonth()
{
    $qb = $this->createQueryBuilder('b')
        ->select('b.date as date, COUNT(b.id) as bookingsCount')
        ->groupBy('date')
        ->orderBy('date', 'ASC');

    return $qb->getQuery()->getResult();
}
public function countTotalBookings(): int
{
    return $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->getQuery()
        ->getSingleScalarResult();
}

}
