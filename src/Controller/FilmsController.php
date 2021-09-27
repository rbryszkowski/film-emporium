<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\FeatureFilm;
use App\Entity\Film;
use App\Entity\Genre;
use App\Events\FilmAddedEvent;
use App\Events\FilmDeletedEvent;
use App\Exceptions\FilmNotFoundException;
use App\Form\FilmType;
use App\Service\OmdbHttpRequest;
use GuzzleHttp\Client;
use PhpParser\Node\Expr\Cast\Object_;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilmsController extends AbstractController
{

    public function filmIndexPage(Request $request, OmdbHttpRequest $omdbReq): Response
    {

        $search = $request->get('search', '');

        $selectedGenre = $request->get('genres', '');

        $filmsReturned = $this->getDoctrine()->getRepository(Film::class)->findBySearch($search, $selectedGenre);

        $featuredFilmIds = $this->getDoctrine()->getRepository(FeatureFilm::class)->findAll();

        $featuredFilms = [];
        $featuredFilmsOmdb = [];

        $allFilms = $this->getDoctrine()->getRepository(Film::class)->findAll();

        foreach($featuredFilmIds as $featuredId) {
            $featuredFilm = $this->getDoctrine()->getRepository(Film::class)->find($featuredId->getFilmId());
            if ($featuredFilm) {
                $featuredFilms[] = $featuredFilm;
            }
        }

        foreach($featuredFilms as $featuredFilm) {
            try {
                $featuredFilmsOmdb[] = $omdbReq->getFilmByTitle($featuredFilm->getTitle());
            } catch(FilmNotFoundException $e) {
                $featuredFilmsOmdb[] = null;
            }
        }

        $genres = $this->getDoctrine()->getRepository(Genre::class)->findAll();

        return $this->render('films/index.html.twig', [
            'films' => $filmsReturned,
            'featured' => $featuredFilms,
            'featuredOmdb' => $featuredFilmsOmdb,
            'genres' => $genres,
            'search' => $search,
            'selectedGenre' => $selectedGenre
        ]);

    }

    public function filmDetailsPage(int $id, OmdbHttpRequest $omdbReq): Response
    {
        $em = $this->getDoctrine()->getManager();

        $film = $em->getRepository(Film::class)->find($id);

        if (!$film) {
            throw $this->createNotFoundException('The film does not exist');
        }

        try {
            $omdbFilmData = $omdbReq->getFilmByTitle($film->getTitle());
        } catch(FilmNotFoundException $e) {
            $omdbFilmData = null;
        }

        return $this->render('films/filmDetailsPage.html.twig', [
            'film' => $film,
            'omdbData' => $omdbFilmData
        ]);
    }

    public function addFilmPage(Request $request, EventDispatcherInterface $dispatcher): Response
    {

        $filmModel = new Film();

        $form = $this->createForm(FilmType::class, $filmModel);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($filmModel);
                $manager->flush();
                //dispatch a film added event
                $filmAddedEvent = new FilmAddedEvent($filmModel);
                $dispatcher->dispatch($filmAddedEvent, FilmAddedEvent::NAME);

                return $this->redirectToRoute('filmIndexPage');
            }

        }

        return $this->render('films/addFilmPage.html.twig', [
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

        return $this->render('films/updateFilmPage.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function deleteFilm(int $id, EventDispatcherInterface $dispatcher): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if ($film !== null) {
            $entityManager->remove($film);
            $entityManager->flush();
            $deleteFilmEvent = new FilmDeletedEvent($film);
            $dispatcher->dispatch($deleteFilmEvent, FilmDeletedEvent::NAME);
        }

        return $this->redirectToRoute('filmIndexPage');
    }

    public function deleteAllFilms(EventDispatcherInterface $dispatcher) {

        $entityManager = $this->getDoctrine()->getManager();
        $allFilms = $entityManager->getRepository(Film::class);
        $allFilms->deleteAll();
        $entityManager->flush();

        return $this->redirectToRoute('filmIndexPage');

    }

}