<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenresController extends AbstractController
{

    public function manageGenresPage(Request $request): Response
    {

        $genreModel = new Genre();

        $form = $this->createForm(GenreType::class, $genreModel);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($genreModel);
                $manager->flush();
            }

        }

        $em = $this->getDoctrine()->getManager();
        $genres = $em->getRepository(Genre::class)->findAll();

        return $this->render('genres/manageGenresPage.html.twig', [
            'genres' => $genres,
            'form' => $form->createView()
        ]);

    }

    public function deleteGenre(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $genre = $em->getRepository(Genre::class)->find($id);

        if ($genre !== null) {
            $em->remove($genre);
            $em->flush();
        }

        return $this->redirectToRoute('manageGenresPage');
    }

    public function deleteAllGenres() {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getRepository(Genre::class)->deleteAll();
        $entityManager->flush();

        return $this->redirectToRoute('manageGenresPage');
    }


}