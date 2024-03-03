<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }
    public function searchByName($name)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }
    public function findByPreferences($region, $visaRequired, $language, $transportType, $accommodationType, $tripDuration)
{
    $qb = $this->createQueryBuilder('c');

    // Join with Reservation entity
    $qb->leftJoin('c.accomodations', 'a')
       ->leftJoin('a.reservations', 'r')
       ->leftJoin('c.guides', 'g')
       ->leftJoin('c.transports', 't')
       ->where('c.region = :region')
       ->andWhere('c.visaRequired = :visaRequired')
       ->andWhere('g.language = :language')
       ->andWhere('t.typeT = :transportType')
       ->andWhere('a.type = :accommodationType')
       // Calculate the duration and compare it with the user's preferred trip duration
       ->andWhere('DATEDIFF(r.endDate, r.startDate) = :tripDuration')
       ->setParameters([
           'region' => $region,
           'visaRequired' => $visaRequired,
           'language' => $language,
           'transportType' => $transportType,
           'accommodationType' => $accommodationType,
           'tripDuration' => $tripDuration,
       ]);

    $query = $qb->getQuery();

    return $query->getResult();
}
public function findByRegion($region)
{
    return $this->createQueryBuilder('c')
        ->where('c.region = :region')
        ->setParameter('region', $region)
        ->getQuery()
        ->getResult();
}
public function findByRegionAndVisaRequired(string $region, bool $visaRequired): array
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.region = :region')
        ->andWhere('c.VisaRequired = :visaRequired')
        ->setParameter('region', $region)
        ->setParameter('visaRequired', $visaRequired)
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Country[] Returns an array of Country objects
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

//    public function findOneBySomeField($value): ?Country
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
