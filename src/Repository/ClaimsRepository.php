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
public function countClaimsByCategory()
{
    $qb = $this->createQueryBuilder('c')
        ->select("fkC.name AS categoryName, COUNT(c.id) AS claimsCount")
        ->leftJoin('c.fkC', 'fkC') // Assuming 'category' is the property in the claims entity that references the category entity
        ->groupBy('categoryName')
        ->orderBy('categoryName', 'ASC');

    return $qb->getQuery()->getResult();
}
public function findBySearchCriteriaAndSort(?string $searchTerm, $sortField = 'id', $sortOrder = 'ASC'): array
{
    $qb = $this->createQueryBuilder('c')
               ->leftJoin('c.fkC', 'Categories'); // Assuming 'fkC' is your ManyToOne relation to Categories

    if ($searchTerm) {
        $qb->where('c.title LIKE :term OR c.description LIKE :term OR c.createDate LIKE :term OR c.state LIKE :term OR Categories.name LIKE :term OR c.reply LIKE :term ')
           ->setParameter('term', '%' . $searchTerm . '%');
    }


    // Add criteria conditions here, if any

    if ($sortField && in_array($sortField, ['id', 'title', 'description', 'createDate', 'state', 'reply'])) {
        $qb->orderBy('c.' . $sortField, $sortOrder);
    }

    return $qb->getQuery()->getResult();
}
}
