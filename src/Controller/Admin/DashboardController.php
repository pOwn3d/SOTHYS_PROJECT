<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use App\Entity\Plv;
use App\Entity\Promotion;
use App\Entity\PromotionItem;
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
        yield MenuItem::section('Gestion des Utilisateurs')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::section('Gestion des commandes');
        yield MenuItem::linkToCrud('Commande', 'fas fa-store-alt', Order::class);
        yield MenuItem::linkToCrud('Promotion', 'fas fa-percent', Promotion::class);
        yield MenuItem::linkToCrud('Promotion Produit', 'fas fa-asterisk', PromotionItem::class);
        yield MenuItem::linkToCrud('PLV', 'fas fa-toolbox', Plv::class);
        yield MenuItem::section('Gestion des societe')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Societe', 'fas fa-store-alt', Society::class)->setPermission('ROLE_ADMIN');
    }
}
