<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType {

    /**
     * On construit le formulaire en question
     *
     * @param FormBuilderInterface $builder Interface de construction de formulaire pour effectué la création de celui-ci
     * @param array $option Un tableau récupérant différentes options pour le formulaire
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        
        // ⬇️ Initialise les champs du formulaire ⬇️ //

        $builder->add('query', SearchType::class, [ 'required' => false ]);

    }   

                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */
        
    /**
     * Configure des options nécessaires pour le formulaire en question
     *
     * @param OptionsResolver $resolver Un instance de OptionsResolver
     * 
     */
    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults([]); }
}
