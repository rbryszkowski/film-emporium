<?php

namespace App\Repository;

use App\Entity\FilmLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FilmLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilmLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilmLog[]    findAll()
 * @method FilmLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilmLog::class);
    }

    // /**
    //  * @return FilmLog[] Returns an array of FilmLog objects
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
    public function findOneBySomeField($value): ?FilmLog
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
