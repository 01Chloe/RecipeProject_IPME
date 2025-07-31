<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddCommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre du commentaire doit être remplis',
                    ])
                ],
            ])
            ->add('comment', null, [
                'label' => 'Commentaire',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le commentaire doit être remplis',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
