<?php

namespace App\Services;

use App\Entity\Recipe;
use App\Entity\User;
use App\Enum\RecipeStatusEnum;
use App\Form\RecipeFormFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class RecipeServices
{
    public function __construct(
        private EntityManagerInterface $em,
        private Environment            $twig,
        private UrlGeneratorInterface  $generator
    )
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handleRecipeForm(
        RecipeFormFlow $recipeFormFlow,
        Recipe $recipe,
        User $user
    ): Response {
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
                $recipeFormFlow->reset();

                return new RedirectResponse(
                    $this->generator->generate('app_profile')
                );
            }
        }

        dump($recipeFormFlow->getCurrentStep());

        return new Response(
            $this->twig->render('profile/index.html.twig', [
                'flow' => $recipeFormFlow,
                'form' => $form->createView(),
            ])
        );
    }
}
