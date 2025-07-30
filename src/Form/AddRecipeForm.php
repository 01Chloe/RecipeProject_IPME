<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRecipeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Difficulté',
                'expanded' => true,
                'choices' => [
                    'recipe.level.1' => 1,
                    'recipe.level.2' => 2,
                    'recipe.level.3' => 3,
                ],
            ])
            ->add('duration', null, [
                'label' => 'Durée',
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('imagePath', FileType::class, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'input'
                ],
                'mapped' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'translation_domain'=>'front'
        ]);
    }
}
