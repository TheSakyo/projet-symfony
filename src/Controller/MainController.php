<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController {   
    
    /**
     * Effectue un 'render()' en précisant les paramètres à envoyés 
     *
     * @param string $renderFile Le fichier à envoyé à la vue
     * @param string|null $parameters Un tableau de paramètre à envoyé à la vue
     * 
     * @return Response Une réponse pour la vue
     */
    #[Route]
    public function index(string $renderFile, array|null $parameters = null): Response { 

        $parameters['navbar'] = [

            [ 'url' => '/home', 'path' => 'home', 'label' => 'Accueil'],
            [ 'url' => '/article', 'path' => 'articles_list', 'label' => 'Liste des Articles'],
        ];
                     /* --------------------------------------- */

        return $this->render($renderFile, $parameters); 
    }

                 /* --------------------------------------------------------- */

    /**
     * Créer un formulaire et envoie la requête récupéré, en précisant ses données et son type
     * 
     * @param Request $request La requête à récupéré
     * @param string $type La class du type de formulaire
     * @param mixed|null $data Les données à mettre à jour
     * 
     * @return FormInterface Un formulaire en fonction des données entrées
    */
    public function form(Request $request, string $type, mixed $data = null): FormInterface { 

        $form = $this->createForm($type, $data); // On créer le formulaire
        $form->handleRequest($request); // On envoie la requête

        return $form; // On renvoie le formulaire
    }
}
