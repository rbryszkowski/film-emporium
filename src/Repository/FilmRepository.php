<?php

namespace App\Repository;

use App\Entity\Director;
use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * @return Film[]
     */

    public function findBySearch(string $searchTerm, string $genre): array
    {

        $qb = $this->_em->createQueryBuilder();

        $query = $qb
            ->select('film')
            ->from(Film::class, 'film')
            ->leftJoin('film.director', 'director')
            ->leftJoin('film.genres', 'genre')
            ->where(
                $qb->expr()->like('film.title', ':search')
            )
            ->orWhere(
                $qb->expr()->like('film.description', ':search')
            )
            ->orWhere(
                $qb->expr()->like('director.name', ':search')
            )
            ->setParameter('search', '%' . $searchTerm . '%');

        if ($genre)
        {
            $qb
                ->andWhere(
                    $qb->expr()->eq('genre.name', ':genre')
                )
                ->setParameter('genre', $genre)
            ;
        }

        dump($query->getQuery());

        return $query->getQuery()->getResult();

    }

    public function deleteAll()
    {
        $qb = $this->createQueryBuilder('films');

        return $qb->delete()->getQuery()->getResult();

    }

    // /**
    //  * @return Film[] Returns an array of Film objects
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
    public function findOneBySomeField($value): ?Film
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
