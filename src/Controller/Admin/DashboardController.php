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
     * @Route("/{_locale}/admin", name="admin", requirements={
    * "_locale"="%app.locales%"
    * })
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(AdminUserController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<a href="/"><img src="../assets/images/logo_register.png"></a>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('settings.user');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::section('settings.order');
        yield MenuItem::linkToCrud('Order', 'fas fa-store-alt', Order::class);
        yield MenuItem::linkToCrud('Promotion', 'fas fa-percent', Promotion::class);
        yield MenuItem::linkToCrud('Promotion Produit', 'fas fa-asterisk', PromotionItem::class);
        yield MenuItem::linkToCrud('PLV', 'fas fa-toolbox', Plv::class);
        yield MenuItem::section('settings.society');
        yield MenuItem::linkToCrud('Society', 'fas fa-store-alt', Society::class);
    }
}
