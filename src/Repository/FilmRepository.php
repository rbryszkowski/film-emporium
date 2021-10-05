<?php

namespace App\Repository;

use App\Entity\Director;
use App\Entity\Film;
use App\Events\FilmDeletedEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    private $dispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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

        return $query->getQuery()->getResult();

    }

    public function deleteAll()
    {

        $allFilms = $this->findAll();

        $qb = $this->createQueryBuilder('films');

//        $allFilms = $qb
//            ->select('partial f.{title}')
//            ->from(Film::class, 'f')
//            ->getQuery()
//            ->getResult(); // this code is incomplete..
//
//        $dql = dump($qb->getDQL());

        $query = $qb->delete()->getQuery()->getResult();

        //dispatch delete event for each film
        foreach($allFilms as $film) {
            $deleteFilmEvent = new FilmDeletedEvent($film);
            $this->dispatcher->dispatch($deleteFilmEvent, FilmDeletedEvent::NAME);
        }

        return $query;

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
