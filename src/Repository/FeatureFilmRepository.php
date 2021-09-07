<?php

namespace App\Repository;

use App\Entity\FeatureFilm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FeatureFilm|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeatureFilm|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeatureFilm[]    findAll()
 * @method FeatureFilm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatureFilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeatureFilm::class);
    }

    public function deleteAll()
    {
        $qb = $this->createQueryBuilder('films');

        return $qb->delete()->getQuery()->getResult();

    }

    // /**
    //  * @return FeatureFilm[] Returns an array of FeatureFilm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeatureFilm
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
