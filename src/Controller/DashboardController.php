<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
