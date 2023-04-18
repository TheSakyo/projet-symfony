<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;


class CommentCrudController extends AbstractCrudController {
    
    public static function getEntityFqcn(): string { return Comment::class; }
 
            /* ----------------------------------------------------- */
    
    public function configureFields(string $pageName): iterable {

                /* -------------------------------------------- */

        return [ 
            
            TextareaField::new('content', 'Contenu'),
            AssociationField::new('user', 'Utilisateur')->formatValue(function($value, $entity) { return $entity->getUser()->getName(); })
            ->setFormTypeOptions([
                'class' => User::class,
                'choice_label' => 'name'
            ]),
            AssociationField::new('article', 'Article')->formatValue(function($value, $entity) { return $entity->getArticle()->getTitle(); })
            ->setFormTypeOptions([
                'class' => Article::class,
                'choice_label' => 'title'
            ]),
            DateTimeField::new('date')->hideOnForm() // Champ qui sera visible sauf pour le formulaire
        ];
    }


            /* ----------------------------------------------------- */

   public function createEntity(string $entityFqcn) {
    
        // Création d'un objet Comment vide
        $comment = new comment(); 
        
        // Ajout de l'utilisateur qui l'a créé
        $comment->setUser($this->getUser());
        
        // Ajout de la date de création
        $comment->setDate(new DateTimeImmutable());
        
        return $comment; // Retourne le commentaire
    }
}
