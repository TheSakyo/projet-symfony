<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends MainController {   

    #[Route('/', name: 'none')]
    public function none(): Response { return $this->redirectToRoute('home'); }

    #[Route('/home', name: 'home')]
    public function home(): Response { return $this->index('pages/index.html.twig'); }

            /* ---------------------------------------- */
}
