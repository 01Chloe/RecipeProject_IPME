<?php

namespace App\Services;

use App\Entity\Recipe;
use App\Entity\User;
use App\Enum\RecipeStatusEnum;
use App\Form\RecipeFormFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class RecipeServices
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {

    }

    public function handleRecipeForm(
        RecipeFormFlow $recipeFormFlow,
        Recipe $recipe,
        User $user
    ): array {
        $recipeFormFlow->bind($recipe);
        $form = $recipeFormFlow->createForm();

        if ($recipeFormFlow->isValid($form)) {
            $recipeFormFlow->saveCurrentStepData($form);

            if($recipeFormFlow->nextStep()) {
                $form = $recipeFormFlow->createForm();
            } else {
                $recipe->setUser($user);
                $recipe->setCreatedAt(new \DateTime());
                $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_IN_VALIDATION);
                $this->em->persist($recipe);
                $this->em->flush();
            }
        }
        return ['flow' => $recipeFormFlow, 'form' => $form];
    }
}
