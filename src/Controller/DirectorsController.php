<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Film;
use App\Form\DirectorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DirectorsController extends AbstractController
{

    public function manageDirectorsPage(Request $request): Response
    {

        $directorModel = new Director();

        $form = $this->createForm(DirectorType::class, $directorModel);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($directorModel);
                $manager->flush();
                $this->addFlash('success', 'the director: ' . $directorModel->getName() . ' has been added!');
                return $this->redirectToRoute('manageDirectorsPage');
            }

        }

        $em = $this->getDoctrine()->getManager();
        $directors = $em->getRepository(Director::class)->findAll();

        return $this->render('directors/manageDirectorsPage.html.twig', [
            'directors' => $directors,
            'form' => $form->createView()
        ]);

    }

    public function deleteDirector(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $director = $em->getRepository(Director::class)->find($id);

        $associatedFilms = $em->getRepository(Film::class)->findBy(['director' => $director]);

        foreach ($associatedFilms as $film) {
            $film->setDirector(null);
            $em->persist($film);
            $em->flush();
        }

        if ($director !== null) {
            $em->remove($director);
            $em->flush();
        }

        return $this->redirectToRoute('manageDirectorsPage');

    }

    public function deleteAllDirectors(): Response {

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->getRepository(Director::class)->deleteAll();

        $allFilms = $entityManager->getRepository(Film::class)->findAll();
        //set director property in all films to null
        foreach ($allFilms as $film) {
            $film->setDirector(null);
            $entityManager->persist($film);
        }

        $entityManager->flush();

        return $this->redirectToRoute('manageDirectorsPage');

    }


}