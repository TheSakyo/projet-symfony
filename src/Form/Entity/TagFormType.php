<?php

namespace App\Form\Entity;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class TagFormType extends AbstractType {

    /**
     * On construit le formulaire en question
     *
     * @param FormBuilderInterface $builder Interface de construction de formulaire pour effectué la création de celui-ci
     * @param array $option Un tableau récupérant différentes options pour le formulaire
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        // ⬇️ Initialise les champs du formulaire ⬇️ //

        $builder->add('title', TextType::class, [

            'label' => 'Titre de la Catégorie :',
            'attr' => [ 'placeholder' => 'Mon Super Titre' ],
            'constraints' => [ new NotBlank(), new NotNull() ],
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
                
                $title = $form->get('title')->getData(); // Récupère le champ titre
        
                                /* ---------------------------------- */

                // Si le champ titre est vide, on définit une erreur disant que c'est invalide
                if($form->has('title') && ctype_space($title) || empty($title)) { $errors[] = "Le titre est invalide !"; }
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
    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults([ 'data_class' => Tag::class ]); }
}
