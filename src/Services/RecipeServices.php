<?php

namespace App\Services;

use App\Entity\Recipe;
use App\Entity\User;
use App\Enum\RecipeStatusEnum;
use App\Form\RecipeFormFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
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
        private UrlGeneratorInterface  $generator,
        private RequestStack $requestStack
    )
    {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handleRecipeFormAction(
        RecipeFormFlow $flow,
        Recipe $recipe,
        User $user,
        FileUploaderService $fileUploaderService
    ): Response {
        $flow->bind($recipe);
        $form = $flow->createForm();
        $session = $this->requestStack->getSession();

        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->getCurrentStep() === 1) {
                /** @var UploadedFile|null $file */
                $file = $form->get('imagePath')->getData();
                // Stock in image path in session
                if ($file !== null) {
                    $session->set(
                        'filename_recipe',
                        $fileUploaderService->uploadFile($file, '/recipe')
                    );
                }
            }

            if($flow->nextStep()) {
                $form = $flow->createForm();
            } else {
                if ($session->has('filename_recipe')) {
                    $recipe->setImagePath($session->get('filename_recipe'));
                    $session->remove('filename_recipe');
                }
                $recipe->setUser($user);
                $recipe->setCreatedAt(new \DateTime());
                $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_IN_VALIDATION);
                foreach ($recipe->getRecipeIngredients() as $recipeIngredient){
                    $recipeIngredient->setRecipe($recipe);
                }
                $this->em->persist($recipe);
                $this->em->flush();
                $flow->reset();

                return new RedirectResponse(
                    $this->generator->generate('app_profile')
                );
            }
        }

        return new Response(
            $this->twig->render('profile/index.html.twig', [
                'flow' => $flow,
                'form' => $form->createView(),
            ])
        );
    }
}
