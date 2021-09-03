<?php

namespace App\Controller;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenresController extends AbstractController
{

    public function manageGenresPage(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {

            $genreToAdd = $request->get('genreToAdd', '');
            $genreModel = new Genre();
            $genreModel->setName($genreToAdd);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($genreModel);
            $manager->flush();
        }

        $genres = $em->getRepository(Genre::class)->findAll();

        return $this->render('films/manageGenresPage/manageGenresPage.html.twig', [
            'genres' => $genres
        ]);

    }

    public function deleteGenre(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $genre = $em->getRepository(Genre::class)->find($id);

        if ($genre !== NULL) {
            $em->remove($genre);
            $em->flush();
        }

        return $this->redirectToRoute('manageGenresPage');
    }


}