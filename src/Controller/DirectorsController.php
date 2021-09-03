<?php

namespace App\Controller;

use App\Entity\Director;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DirectorsController extends AbstractController
{

    public function manageDirectorsPage(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {

            $directorToAdd = $request->get('directorToAdd', '');
            $directorModel = new Director();
            $directorModel->setName($directorToAdd);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($directorModel);
            $manager->flush();
        }

        $directors = $em->getRepository(Director::class)->findAll();

        return $this->render('films/manageDirectorsPage/manageDirectorsPage.html.twig', [
            'directors' => $directors
        ]);

    }

    public function deleteDirector(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $director = $em->getRepository(Director::class)->find($id);

        if ($director !== NULL) {
            $em->remove($director);
            $em->flush();
        }

        return $this->redirectToRoute('manageDirectorsPage');
    }


}