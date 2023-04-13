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

    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder->add('email', EmailType::class, [ 'constraints' => [ new NotBlank(), new NotNull() ] ]);

                        /* -------------------------------- */

        $builder->add('name', TextType::class, [ 'constraints' => [ new NotBlank([]), new NotNull() ] ]);

                        /* -------------------------------- */

        $builder->add('password', PasswordType::class, [ 'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank(), 
                new NotNull(),
                new Length([
                    'min' => 6, // Longueur minimale autorisée
                    'max' => 4096 // Longueur maximale autorisée par Symfony pour des raisons de sécurité
                ])
            ]
        ]);

                        /* -------------------------------- */

        $builder->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [ new IsTrue() ]
        ]);
    }
                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */

    public static function checkErrors(FormInterface $form) {

        $errors = null;

                /* --------------------------------- */
                
        if($form->isEmpty()) {

            $parameters['errorForm'][] = "Veuillez remplir le formulaire";

        } else {

            $email = $form->get('email')->getData();
            $name = $form->get('name')->getData();
            $password = $form->get('password')->getData();
    
                            /* ---------------------------------- */
    
            if($form->has('email') && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    
                $errors[] = "L'Adresse Mail est invalide !";
            } 
            
            if($form->has('name') && (ctype_space($name) || empty($name))) {
    
                $errors[] = "Le nom est invalide !";
            }
    
            if($form->has('password')) {
    
                if(strlen($password) < 6 || strlen($password) > 4096) {
                    
                    $errors[] = "Le mot de passe doit comporter minimum 6 caractères et maximum 4096 caractères !";
                
                } else if(ctype_space($password) || empty($password)) {
    
                    $errors[] = "Le mot de passe est invalide !";
                }
            }
        }
                /* --------------------------------- */

        return $errors;
    }

                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */

    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults(['data_class' => User::class ]); }
}
