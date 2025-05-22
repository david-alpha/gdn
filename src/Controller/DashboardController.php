<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
	#[IsGranted('ROLE_USER', 'ROLE_ADMIN')]
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
    public function dashboardAdmin(EntityManagerInterface $entityManager): Response
    {
		//récupération de tous les users
        $users = $entityManager->getRepository(User::class)->findAll();
		//ce retrun renvoie vers template/dashboard/admin
		return $this->render('dashboard/admin.html.twig', [
            'users' => $users,
        ]);
    }
}
