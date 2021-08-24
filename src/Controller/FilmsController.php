<?php

namespace App\Controller;

use App\Entity\Film;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class FilmsController extends AbstractController
{

    public function addFilm(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $title = $_POST['title'];
        $genre = $_POST['genre'];
        $description = $_POST['description'];

        $film = new Film();
        $film->setTitle($title);
        $film->setGenre($genre);
        $film->setDescription($description);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($film);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->json($film);
    }

    public function updateFilm(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);
        $title = $_POST['newTitle'];
        $genre = $_POST['newGenre'];
        $description = $_POST['newDescription'];

        $film->setTitle($title);
        $film->setGenre($genre);
        $film->setDescription($description);

        // $entityManager->persist($film);
        $entityManager->flush();

        return $this->json($film);
    }

    public function deleteFilm(int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        $entityManager->remove($film);

        // $entityManager->persist($film);
        $entityManager->flush();

        return $this->json($film);

    }

    public function findMatching(string $searchBy='title', string $input): Response
    {

        if ($searchBy === 'film_id') {$searchBy = 'id';}

        $results = $this->getDoctrine()->getRepository(Film::class)->findBy([$searchBy => $input]);

        // $results = $this->getDoctrine()->getRepository(Film::class)->findAllLike($searchBy, $input);

        return $this->json($results);

    }

    public function showAll(): Response
    {

        $results = $this->getDoctrine()->getRepository(Film::class)->findAll();

        return $this->json($results);

    }

}
