<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController {

    #[Route('/admin', name: 'admin')]
    public function index(): Response {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }
            /* ----------------------------------------------------- */
            CECI EST UN FUCKING TEST
    public function configureDashboard(): Dashboard {

        return Dashboard::new()
            ->setTitle('Symfony - Admin')
            ->setTextDirection('ltr')
            ->renderContentMaximized()
            ->disableUrlSignatures()
            ->generateRelativeUrls();
    }
            /* ----------------------------------------------------- */
    
    public function configureMenuItems(): iterable  {

        return [

            MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home'),
            MenuItem::section(),
            MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Article::class),
            MenuItem::linkToCrud('Tags', 'fa fa-tags', Tag::class),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)
        ];
    }
}
