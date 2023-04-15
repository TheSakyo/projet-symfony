<?php

namespace App\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController {   
    
    /**
     * Effectue un 'render()' en précisant les paramètres à envoyés 
     *
     * @param string $renderFile Le fichier à envoyé à la vue
     * @param string|null $parameters Un tableau de paramètre à envoyé à la vue
     * 
     * @return Response Une réponse pour la vue
     */
    public function index(string $renderFile, array|null $parameters = null): Response { 

        // Définit un tableau pour le menu de navigation //
        $parameters['navbar'] = [

            [ 'url' => '/home', 'path' => 'home', 'label' => 'Accueil'],
            [ 'url' => '/article', 'path' => 'article_list', 'label' => 'Liste des Articles'],
        ];
        // Définit un tableau pour le menu de navigation //

                     /* --------------------------------------- */
        
        return $this->render($renderFile, $parameters); // Retourne la vue associé
    }

                 /* --------------------------------------------------------- */
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

                    /* --------------------------------------------- */

        // ⬇️ On essaie d'envoyer la requête vers l'objet en question à mettre à jour ⬇️ //
        try { $form->handleRequest($request); } 
        catch(InvalidArgumentException) {}
        // ⬆️ On essaie d'envoyer la requête vers l'objet en question à mettre à jour ⬆️ //
        
                    /* --------------------------------------------- */

        return $form; // On renvoie le formulaire dont il est question
    }
}
