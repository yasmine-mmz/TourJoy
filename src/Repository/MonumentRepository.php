<?php

namespace App\Repository;

use App\Entity\Monument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monument>
 *
 * @method Monument|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monument|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monument[]    findAll()
 * @method Monument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monument::class);
    }
    public function findAllSortedByPrice()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.entryprice', 'ASC') // Assuming 'entryPrice' is the field representing the monument's price
            ->getQuery()
            ->getResult();
    }
    public function findOneById($id): ?Monument
    {
        return $this->findOneBy(['ref' => $id]);
    }
    public function searchByName($name)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nameM LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function searchByEntryPrice($price)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.entryprice = :price')
            ->setParameter('price', $price)
            ->getQuery()
            ->getResult();
    }

    public function searchByCountry($country)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.fkcountry', 'c')
            ->andWhere('c.name LIKE :country')
            ->setParameter('country', $country)
            ->getQuery()
            ->getResult();
    }
    public function countMonumentsByCountry()
    {
        $qb = $this->createQueryBuilder('m')
            ->select("c.name AS country, COUNT(m.ref) AS monumentCount")
            ->innerJoin('m.fkcountry', 'c')
            ->groupBy('c.name')
            ->orderBy('c.name', 'ASC');
    
        return $qb->getQuery()->getResult();
    }
    public function createQueryBuilderForAll()
    {
        return $this->createQueryBuilder('m');
    }

    public function createQueryBuilderForSearchByName($name)
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :name')
            ->setParameter('name', '%' . $name . '%');
    }
    
//    /**
//     * @return Monument[] Returns an array of Monument objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Monument
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
