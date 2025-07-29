<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddIngredientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipeIngredients', CollectionType::class, [
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'data-list-selector' => 'ingredient',
                ]
            ])
            ->add('addIngredient',ButtonType::class, [
                'label' => 'Ajouter un ingrédent',
                'attr' => [
                    'class' => 'btn btn-center',
                    'data-btn-selector' => 'ingredient',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
