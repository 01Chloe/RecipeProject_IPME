<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'label' => false,
                'class' => Ingredient::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-select',
                ],
                'choice_label' => 'name',
            ])
            ->add('quantity', TextType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'input large'
                ],
                'label_attr' => [
                    'class' => 'label'
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,

        ]);
    }
}
