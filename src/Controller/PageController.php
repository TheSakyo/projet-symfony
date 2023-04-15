<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends MainController {   

    /**
     * Redirige l'Utilisateur vers la page d'accueil avec la route 'home'
     * 
     * @return Response Une redirection de route
     */
    #[Route]
    public function none(): Response { return $this->redirectToRoute('home'); }

                /* ----------------------------------------------------------------------- */
                /* ----------------------------------------------------------------------- */

    /**
     * Redirige l'Utilisateur vers la page d'accueil avec la route 'home'
     * 
     * @return Response Une vue associé
     */
    #[Route('/home', name: 'home')]
    public function home(): Response { return $this->index('pages/index.html.twig'); }
}
