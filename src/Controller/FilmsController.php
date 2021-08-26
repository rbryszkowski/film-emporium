<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Film;

use App\Entity\Genre;
use App\Form\FilmType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilmsController extends AbstractController
{
    public function index(Request $request, ValidatorInterface $validator) : Response
    {
        $filmModel = new Film();

        $form = $this->createForm(FilmType::class, $filmModel);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($filmModel);
                $manager->flush();
            }

        }

        // all our results currently
        $films = $this->getDoctrine()->getRepository(Film::class)->findAll();

        return $this->render('films/index.html.twig', [
            'form' => $form->createView(),
            'films' => $films
        ]);

    }

    public function addFilm(Request $request): Response
    {

        //get title, desc., and director from request
        $title = $request->get('title');
        $description = $request->get('description');
        $director_name = $request->get('director');

        //create new director object
        $director = new Director();
        $director->setName($director_name);

        $film = new Film();
        $film->setTitle($title);
        $film->setDescription($description);
        //relates this film to the director
        $film->setDirector($director);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($film);
        $entityManager->persist($director);
        $entityManager->flush();

        return $this->json($film);

    }

    public function findMatching(string $searchBy='title', string $input): Response
    {

        if ($searchBy === 'film_id') {$searchBy = 'id';}

        $results = $this->getDoctrine()->getRepository(Film::class)->findBy([$searchBy => $input]);

        return $this->json($results);

    }

    public function showAll(): Response
    {

        $results = $this->getDoctrine()->getRepository(Film::class)->findAll();

        return $this->json($results);

    }

    ////////

    public function updateFilm(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);
        $title = $request->get('newTitle');
        $genre = $request->get('newGenre');
        $description = $request->get('newDescription');

        $film->setTitle($title);
        $film->setGenre($genre);
        $film->setDescription($description);

        $entityManager->persist($film);
        $entityManager->flush();

        return $this->json($film);
    }

    public function deleteFilm(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        $entityManager->remove($film);
        $entityManager->flush();

        return $this->redirectToRoute('indexFilm');
    }

}
