<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string { return User::class; }

            /* ----------------------------------------------------- */
    
    public function configureFields(string $pageName): iterable {

        $password = TextField::new('password')
            ->setLabel('Mot de passe de l\'utilisateur')
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->hideOnIndex(); // il ne sera visible que dans le formulaire

                /* ----------------------------------------- */

        return [ 
            
            EmailField::new('email', 'Adresse Mail de l\'Utilisateur'), 
            TextField::new('name', 'Nom de l\'Utilisateur'),
            $password,
            ChoiceField::new('roles', 'Rôles') // Définition du champ Roles à multiple choix
            ->allowMultipleChoices()->autocomplete()->setChoices([
                'Visiteur' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN'
            ])
        ];
    }


            /* ----------------------------------------------------- */

   public function createEntity(string $entityFqcn) {
    
        // Création d'un objet Utilisateur vide
        $user = new User(); 
        
        // Ajout du rôle à l'utilisateur
        $user->setRoles(['ROLE_USER']);
        
        return $user; // Retourne l'utilisateur 
    }
}
