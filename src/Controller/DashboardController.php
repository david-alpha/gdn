<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


use App\Entity\Note;
use App\Entity\Etat;
use App\Entity\Tag;
use Doctrine\Common\Collections\Criteria;


use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
	#[IsGranted('ROLE_USER')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
	#[IsGranted('ROLE_USER')]
	#[Route('/dashboardExemple', name: 'app_dashboard_exemple')]
    public function dashboardExemple(): Response
    {
        return $this->render('dashboard/exemple.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
	
	//fonction exemple
	#[IsGranted('ROLE_ADMIN')]
	#[Route('/dashboardAdmin', name: 'app_dashboard_admin')]
    public function dashboardAdmin(EntityManagerInterface $em): Response
    {
		$user = $this->getUser();

    // Critère 1 : Notes "En cours"
    $notesEnCours = $em->createQueryBuilder()
        ->select('n')
        ->from(Note::class, 'n')
        ->join('n.etat', 'e')
        ->where('e.nom = :etat')
        ->setParameter('etat', 'En cours')
        ->getQuery()
        ->getResult();

    // Critère 2 : Notes taguées "Urgent"
    $notesUrgentes = $em->createQueryBuilder()
        ->select('n')
        ->from(Note::class, 'n')
        ->join('n.tag', 't')
        ->where('t.nom = :tag')
        ->setParameter('tag', 'Urgent')
        ->getQuery()
        ->getResult();

    // Critère 3 : Notes de l'utilisateur connecté
        $notes = $user->getNotes();
		
	// Critère 4 : état = "En cours"
        $etatEnCours = $em->getRepository(Etat::class)->findOneBy(['nom' => 'En cours']);
        $criteriaEtat = Criteria::create()->where(Criteria::expr()->eq('etat', $etatEnCours));
        $notesEnCours2 = $notes->matching($criteriaEtat);


    return $this->render('dashboard/admin.html.twig', [
        'notes_en_cours' => $notesEnCours,
		'notes_en_cours2' => $notesEnCours2,
        'notes_urgentes' => $notesUrgentes,
        'notes_utilisateur' => $notes,
    ]);
    }
	//fonction exemple récupération
	#[IsGranted('ROLE_ADMIN')]
	#[Route('/dashboardForm', name: 'formulaire_exemple')]
	public function formulaireExemple(Request $request): Response
    {
        // Récupère la donnée envoyée par le formulaire
        $exempleInput = $request->request->get('exemple_input');

        // Traitement de la donnée (par exemple, enregistrer dans la base de données ou autre action)

        // Pour l'exemple, on va juste afficher un message de confirmation
        return new Response(
            '<html><body>Formulaire soumis avec succès! Vous avez entré : ' . htmlspecialchars($exempleInput) . '</body></html>'
        );
    }
}
