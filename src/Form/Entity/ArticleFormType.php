<?php

namespace App\Form\Entity;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArticleFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        
        $builder->add('title', TextType::class, [

            'constraints' => [ new NotBlank(), new NotNull() ]
        ]);

                        /* -------------------------------- */

        $builder->add('content', TextareaType::class, [

            'constraints' => [ new NotBlank(), new NotNull() ]
        ]);

                        /* -------------------------------- */

        $builder->add('imageFile', VichFileType::class, [

            'required' => false,
            'allow_delete' => true,
            'asset_helper' => true

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

            $title = $form->get('title')->getData();
            $content = $form->get('content')->getData();
    
                            /* ---------------------------------- */

            if($form->has('title') && ctype_space($title) || empty($title)) {

                $errors[] = "Le titre est invalide !";
            }

            if($form->has('content') && ctype_space($content) || empty($content)) {

                $errors[] = "Le contenu est invalide !";
            }
        }
                /* --------------------------------- */

        return $errors;
    }

                        /* -------------------------------------------------------- */
                        /* -------------------------------------------------------- */

    public function configureOptions(OptionsResolver $resolver): void { $resolver->setDefaults([ 'data_class' => Article::class ]); }
}
