<?php

namespace App\Controller;

use App\Entity\Director;
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

        if ($director !== null) {
            $em->remove($director);
            $em->flush();
        }

        return $this->redirectToRoute('manageDirectorsPage');
    }


}