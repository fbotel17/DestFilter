<?php

// src/Controller/TrajetController.php
namespace App\Controller;

use App\Repository\TrajetRepository;
use App\Entity\Trajet;
use App\Form\TrajetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrajetController extends AbstractController
{
    /**
     * @Route("/trajet/new", name="trajet_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trajet = new Trajet();

        // Créer le formulaire
        $form = $this->createForm(TrajetType::class, $trajet);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le trajet dans la base de données
            $em->persist($trajet);
            $em->flush();

            // Rediriger vers une autre page, par exemple la liste des trajets
            return $this->redirectToRoute('trajet_list');
        }

        // Afficher le formulaire
        return $this->render('trajet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trajets", name="trajet_list")
     */
    public function list(Request $request, TrajetRepository $trajetRepository): Response
    {
        $attribute = $request->query->get('attribute');
        $query = $request->query->get('query');

        if ($attribute && $query) {
            $qb = $trajetRepository->createQueryBuilder('t');
            $qb->where($qb->expr()->like('t.' . $attribute, ':query'))
               ->setParameter('query', '%' . $query . '%');
            $trajets = $qb->getQuery()->getResult();
        } else {
            $trajets = $trajetRepository->findAll();
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('trajet/_trajet_list.html.twig', [
                'trajets' => $trajets,
            ]);
        }

        return $this->render('trajet/list.html.twig', [
            'trajets' => $trajets,
        ]);
    }




    /**
     * @Route("/trajet/{id}/edit", name="trajet_edit")
     */
    public function edit(Trajet $trajet, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('trajet_list');
        }

        return $this->render('trajet/edit.html.twig', [
            'form' => $form->createView(),
            'trajet' => $trajet,
        ]);
    }

    /**
     * @Route("/trajet/{id}/delete", name="trajet_delete", methods={"POST"})
     */
    public function delete(Request $request, Trajet $trajet, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trajet->getId(), $request->request->get('_token'))) {
            $em->remove($trajet);
            $em->flush();
        }

        return $this->redirectToRoute('trajet_list');
    }


    
}
