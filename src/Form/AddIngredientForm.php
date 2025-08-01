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
                // si des éléments non reconnus sont soumis à la collection, ils seront ajoutés en tant que nouveaux éléments. Le tableau final contiendra les éléments existants ainsi que le nouvel élément qui était dans les données soumises.
                'allow_add' => true,
                // si un élément existant n'est pas contenu dans les données soumises, il sera absent du tableau final d'éléments
                // possible d'ajoute un btn supprimer en JS
                'allow_delete' => true,
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => [
                    'label' => false,
                ],
                // permet de relier les ingrédients au bouton d'ajout (grâce au js)
                'attr' => [
                    'data-list-selector' => 'ingredient',
                ]
            ])
            ->add('addIngredient',ButtonType::class, [
                'label' => 'Ajouter un ingrédent',
                'attr' => [
                    'class' => 'btn btn-center',
                    'data-btn-selector' => 'ingredient',
                ]]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
