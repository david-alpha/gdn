<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatForm;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/etat')]
final class EtatController extends AbstractController
{
    #[Route(name: 'app_etat_index', methods: ['GET'])]
    public function index(EtatRepository $etatRepository): Response
    {
        return $this->render('etat/index.html.twig', [
            'etats' => $etatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etat = new Etat();
        $form = $this->createForm(EtatForm::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etat);
            $entityManager->flush();

            return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etat/new.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etat_show', methods: ['GET'])]
    public function show(Etat $etat): Response
    {
        return $this->render('etat/show.html.twig', [
            'etat' => $etat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etat $etat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtatForm::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etat/edit.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etat_delete', methods: ['POST'])]
    public function delete(Request $request, Etat $etat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($etat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
    }
}
