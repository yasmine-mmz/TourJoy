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
            ->orderBy('m.entryprice', 'ASC') 
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
    public function findBySearchValue(string $searchValue): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nameM LIKE :searchValue')
            ->orWhere('m.entryPrice LIKE :searchValue')
            ->orWhere('m.description LIKE :searchValue')
            ->orWhere('m.fkcountry IN (
                SELECT c.id FROM App\Entity\Country c WHERE c.name LIKE :searchValue
            )')
            ->setParameter('searchValue', '%' . $searchValue . '%')
            ->getQuery()
            ->getResult();
    }
    public function searchByNameOrCountry($query)
{
    return $this->createQueryBuilder('m')
        ->leftJoin('m.fkcountry', 'c')
        ->where('LOWER(m.nameM) LIKE :query OR LOWER(c.name) LIKE :query')
        ->setParameter('query', '%' . strtolower($query) . '%')
        ->getQuery()
        ->getResult();
}
public function findByCountryRegion($region): array
{
    return $this->createQueryBuilder('m')
        ->innerJoin('m.fkcountry', 'c')
        ->where('c.region = :region')
        ->setParameter('region', $region)
        ->getQuery()
        ->getResult();
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

public function countTotalMonuments(): int
{
    return $this->createQueryBuilder('m')
        ->select('COUNT(m.ref)')
        ->getQuery()
        ->getSingleScalarResult();
}
}
