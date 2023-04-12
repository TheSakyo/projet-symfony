<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController {
    
    public static function getEntityFqcn(): string { return Article::class; }
 
            /* ----------------------------------------------------- */
    
    public function configureFields(string $pageName): iterable {

                /* -------------------------------------------- */

        return [ 
            
            TextField::new('title', 'Titre'), 
            TextareaField::new('content', 'Contenu'),
            AssociationField::new('user', 'Utilisateur')->formatValue(function($value, $entity) { return $entity->getUser()->getName(); })
            ->setFormTypeOptions([
                'class' => User::class,
                'choice_label' => 'name'
            ]),
            DateTimeField::new('date')->hideOnForm() // Champ qui sera visible sauf pour le formulaire
        ];
    }


            /* ----------------------------------------------------- */

   public function createEntity(string $entityFqcn) {
    
        // Création d'un objet Article vide
        $article = new Article(); 
        
        // Ajout de l'utilisateur qui l'a créé
        $article->setUser($this->getUser());
        
        // Ajout de la date de création
        $article->setDate(new DateTimeImmutable());
        
        return $article;  // Retourne l'article
    }
}
