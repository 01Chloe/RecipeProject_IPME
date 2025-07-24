<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

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
            ->add('level', null, [
                'label' => 'Difficulté',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le niveau de difficulté doit être indiqué',
                    ]),
                    new Range([
                        'notInRangeMessage' => 'Le chiffre doit être compris entre 1 et 3',
                        'min' => 1,
                        'max' => 3,
                    ]),
                ],
            ])
            ->add('duration', null, [
                'label' => 'Durée',
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('imagePath', null, [
                'label' => 'Image',
                'attr' => [
                    'class' => 'input'
                ]
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
        ]);
    }
}
