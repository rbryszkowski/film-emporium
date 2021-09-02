<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Film;

use App\Entity\Genre;
use App\Form\FilmType;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilmsController extends AbstractController
{

    public function filmIndexPage(Request $request): Response
    {

        $search = $request->get('search', '');

        $selectedGenre = $request->get('genres', '');

        $filmsReturned = $this->getDoctrine()->getRepository(Film::class)->findBySearch($search, $selectedGenre);

        $allGenres = $this->getDoctrine()->getRepository(Genre::class)->findAll();

        return $this->render('films/index.html.twig', [
            'films' => $filmsReturned,
            'genres' => $allGenres,
            'search' => $search,
            'selectedGenre' => $selectedGenre
        ]);

    }

    public function filmDetailsPage(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $film = $em->getRepository(Film::class)->find($id);

        $client = new Client();

        $params = http_build_query([
            'apikey' => '34e585c5',
            't' => $film->getTitle()
        ]);

        $url = 'http://www.omdbapi.com/?' . $params;

        $response = $client->request('GET', $url);

        $omdbStatus = json_decode($response->getStatusCode(), true);

        $omdbData = json_decode($response->getBody(), true);

        dump($omdbStatus);
        dump($omdbData);

        return $this->render('films/filmDetailsPage/filmDetailsPage.html.twig', [
            'film' => $film,
            'omdbData' => $omdbData,
            'statusCode' => $omdbStatus
        ]);
    }

    public function addFilmPage(Request $request): Response
    {

        $filmModel = new Film();

        $form = $this->createForm(FilmType::class, $filmModel);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($filmModel);
                $manager->flush();

                return $this->redirectToRoute('filmIndexPage');
            }

        }

        return $this->render('films/addFilmPage/addFilmPage.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function updateFilmPage(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        $form = $this->createForm(FilmType::class, $film);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($film);
                $manager->flush();

                return $this->redirectToRoute('filmIndexPage');
            }

        }

        return $this->render('films/updateFilmPage/updateFilmPage.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function deleteFilm(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if ($film !== NULL) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('filmIndexPage');
    }

}