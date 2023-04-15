<?php

namespace App\Form\Authenticator;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractType {

    /**
     * On construit le formulaire en question
     *
     * @param FormBuilderInterface $builder Interface de construction de formulaire pour effectué la création de celui-ci
     * @param array $option Un tableau récupérant différentes options pour le formulaire
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        // ⬇️ Initialise les champs du formulaire ⬇️ //

        $builder->add('email', EmailType::class, [ 
            
            'label' => 'Adresse Mail :',
            'attr' => [ 'placeholder' => 'example@example.com' ],  
            'constraints' => [ new NotBlank(), new NotNull() ],
            'required' => true
        ]);

                        /* -------------------------------- */

        $builder->add('name', TextType::class, [ 
            
            'label' => 'Nom :',
            'attr' => [ 'placeholder' => 'Votre Nom' ],  
            'constraints' => [ new NotBlank([]), new NotNull() ],
            'required' => true 
        ]);

                        /* -------------------------------- */

        $builder->add('password', PasswordType::class, [ 
            
            'label' => 'Mot de Passe :', 
            'attr' => ['autocomplete' => 'new-password', 'placeholder' => '***********'],
            'constraints' => [
                
                new NotBlank(), 
                new NotNull(),
                new Length([
                    'min' => 6, // Longueur minimale autorisée
                    'max' => 4096 // Longueur maximale autorisée par Symfony pour des raisons de sécurité
                ])
            ],
            'required' => true
        ]);

                        /* -------------------------------- */

        $builder->add('agreeTerms', CheckboxType::class, [ 
            
            'label' => 'Accepter les conditions pour vous inscrire :', 
            'mapped' => false, 'constraints' => [ new IsTrue() ],
            'required' => true 
        ]);
                
        // ⬆️ Initialise les champs du formulaire ⬆️ //
    }
                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */
                        
    /**
     * On vérifie les erreurs de champs du formulaire en question
     *
     * @param FormInterface $form Interface de formulaire pour vérifier les erreurs
     * 
     */
    public static function checkErrors(FormInterface $form) {

        $errors = null; // Permettra de récupérer les erreurs de champs

                /* ------------------------------------------------- */

        // ⬇️ Si le formulaire à bien été envoyé mais qu'il n'est pas valide, on vérifie les erreurs en question ⬇️ //
        if($form->isSubmitted() && !$form->isValid()) {

            // Si le formulaire est vide, on définit une erreur disant qu'il faut remplir le formulaire
            if($form->isEmpty()) { $errors[] = "Veuillez remplir le formulaire"; } 

            // Sinon, on vérifie les erreurs de chaque champs
            else {    
                
                $email = $form->get('email')->getData(); // Récupère le champ de l'adresse mail
                $name = $form->get('name')->getData(); // Récupère le champ du nom
                $password = $form->get('password')->getData(); // Récupère le champ du mot de passe

                                /* ---------------------------------- */

                // Si le champ de l'adresse mail n'est pas valide, on définit une erreur disant que c'est invalide
                if($form->has('email') && !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "L'adresse mail est invalide !"; }

                // Si le champ du nom est vide, on définit une erreur disant que c'est invalide
                if($form->has('name') && ctype_space($name) || empty($name)) { $errors[] = "Le nom est invalide !"; }

                // Si le champ de mot de passe éxiste, on vérifie son nombre de caractère ainsi s'il est vide, on définit une erreur précise
                if($form->has('password')) { 
                    
                    // Si le mot de passe est inférieur à 6 ou supérieur à 4096, alors on définit une erreur pour le nombre de caractères
                    if(strlen($password) < 6 || strlen($password) > 4096) {
                    
                        $errors[] = "Le mot de passe doit comporter minimum 6 caractères et maximum 4096 caractères !";
                        
                    // Sinon, si le mot de passe est vide, alors on définit une erreur disant que c'est invalide
                    } else if(ctype_space($password) || empty($password)) { $errors[] = "Le mot de passe est invalide !"; }
                }
            }
        }
        // ⬆️ Si le formulaire à bien été envoyé mais qu'il n'est pas valide, on vérifie les erreurs en question ⬆️ //

                        /* ------------------------------------------------- */

        return $errors; // Retourne les erreurs si il ont bien été ajoutés
    }

                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */

    /**
     * Configure des options nécessaires pour le formulaire en question
     *
     * @param OptionsResolver $resolver Un instance de OptionsResolver
     * 
     */
    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults(['data_class' => User::class ]); }
}
