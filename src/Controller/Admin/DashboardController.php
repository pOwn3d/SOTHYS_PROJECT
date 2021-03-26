<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use App\Entity\Society;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(AdminUserController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
      
        ->setTitle('<a href="/"><img src="assets/images/logo_register.png"></a>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion des Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::section('Gestion des commandes');
        yield MenuItem::linkToCrud('Commande', 'fas fa-store-alt', Order::class);
        yield MenuItem::section('Gestion des societe');
        yield MenuItem::linkToCrud('Societe', 'fas fa-store-alt', Society::class);
    }
}
