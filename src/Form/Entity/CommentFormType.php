<?php

namespace App\Form\Entity;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CommentFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        
        $builder->add('content', TextareaType::class,  [

            'constraints' => [ new NotBlank(), new NotNull() ]
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

            $content = $form->get('content')->getData();
    
                            /* ---------------------------------- */

            if($form->has('content') && ctype_space($content) || empty($content)) {

                $errors[] = "Le contenu est invalide !";
            }
        }
                /* --------------------------------- */

        return $errors;
    }
                    
                /* -------------------------------------------------------- */
                /* -------------------------------------------------------- */
                    
    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults(['data_class' => Comment::class, ]); }
}
