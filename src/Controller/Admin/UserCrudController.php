<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController {

    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher) {  $this->userPasswordHasher = $userPasswordHasher; }

            /* ----------------------------------------------------- */
            /* ----------------------------------------------------- */
            /* ----------------------------------------------------- */

    public static function getEntityFqcn(): string { return User::class; }

            /* ----------------------------------------------------- */

    public function configureActions(Actions $actions): Actions  {
        
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    }

                    /* -------------------------------- */
                    /* -------------------------------- */
            
    public function configureFields(string $pageName): iterable {

        $password = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setLabel('Mot de passe de l\'utilisateur')
            ->setFormTypeOptions([ 'mapped' => false ])
            ->setRequired($pageName === Crud::PAGE_NEW)
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
                /* ----------------------------------------------------- */
            
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface {
        
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

                                    /* ---------------------------------------------- */

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface {
        
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }
                                    /* ---------------------------------------------- */

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface {

        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function($event) {
            
            /** @var ?User **/
            $user = $this->getUser();

                        /* ----------------------- */

            $form = $event->getForm();
            if(!$form->isValid()) { return; }

            $password = $form->get('password')->getData();
            if($password === null) { return; }

                        /* ----------------------- */
                        
            $hash = $this->userPasswordHasher->hashPassword($user, $password);
            $form->getData()->setPassword($hash);
        });
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
