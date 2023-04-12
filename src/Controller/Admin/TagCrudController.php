<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagCrudController extends AbstractCrudController {
    
    public static function getEntityFqcn(): string { return Tag::class; }

            /* ----------------------------------------------------- */
    
    public function configureFields(string $pageName): iterable {

        return [ 
            
            TextField::new('title', 'Titre du tag'), 
        ];
    }


            /* ----------------------------------------------------- */

    public function createEntity(string $entityFqcn) {
        
        $tag = new Tag(); // Création d'un objet Tag vide
        return $tag; // Retourne le tag
    }
}
