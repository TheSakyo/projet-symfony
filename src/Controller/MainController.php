<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {   

    #[Route]
    public function index(string $renderFile, array|null $parameters = null): Response { 

        $parameters['navbar'] = [

            [ 'url' => '/home', 'path' => 'home', 'label' => 'Accueil'],
            [ 'url' => '/article', 'path' => 'articles_list', 'label' => 'Liste des Articles'],
        ];
                     /* --------------------------------------- */

        return $this->render($renderFile, $parameters); 
    }
}
