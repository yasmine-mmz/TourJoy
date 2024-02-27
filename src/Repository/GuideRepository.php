<?php

namespace App\Repository;

use App\Entity\Guide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guide>
 *
 * @method Guide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guide[]    findAll()
 * @method Guide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guide::class);
    }

//    /**
//     * @return Guide[] Returns an array of Guide objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Guide
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findGuidesFiltered($genders, $ratings, $sortByAge = null)
    {
        $qb = $this->createQueryBuilder('g')
            ->select('g, AVG(f.rating) as avgRating')
            ->leftJoin('g.feedback', 'f')
            ->groupBy('g.CIN');

        if (!empty($genders)) {
            $qb->andWhere('g.genderG IN (:genders)')
               ->setParameter('genders', $genders);
        }

        $havingConditions = [];
        if (in_array('1-3', $ratings)) {
            $havingConditions[] = '(AVG(f.rating) >= 1 AND AVG(f.rating) <= 3)';
        }

        if (in_array('3-5', $ratings)) {
            $havingConditions[] = '(AVG(f.rating) > 3 AND AVG(f.rating) <= 5)';
        }

        if (!empty($havingConditions)) {
            $qb->having(implode(' OR ', $havingConditions));
        }

        // Apply sorting by date of birth if requested
        if ($sortByAge === 'asc' || $sortByAge === 'desc') {
            $qb->orderBy('g.dob', strtoupper($sortByAge));
        }

        return $qb->getQuery()->getResult();
    }
}
