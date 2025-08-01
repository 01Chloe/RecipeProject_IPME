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
        FileUploaderService $fileUploaderService,
        bool $isAdd
    ): Response {
        $flow->bind($recipe);
        $form = $flow->createForm();
        // ouvre une session
        $session = $this->requestStack->getSession();

        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            // si l'utilisateur en est à l'étape de l'ajout de l'image
            if ($flow->getCurrentStep() === 1) {
                /** @var UploadedFile|null $file */
                // recuprère le chemin de l'image
                $file = $form->get('imagePath')->getData();
                // Stock le chemin de l'image en session
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
                // si il une un chemin d'image en session
                if ($session->has('filename_recipe')) {
                    // on le recupère et on le stocke en base de données
                    $recipe->setImagePath($session->get('filename_recipe'));
                    // supprimer le chemin de l'image de la session car plus utile
                    $session->remove('filename_recipe');
                }
                $recipe->setUser($user);
                if($isAdd) {
                    // si c'est un ajout de recette, ajouter une date de création
                    $recipe->setCreatedAt(new \DateTime());
                } else {
                    // sinon changer la date de modification
                    $recipe->setUpdatedAt(new \DateTime());
                }
                // mettre le status de la recette a "à valider"
                $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_IN_VALIDATION);
                // ajouter chaque ingrédients un par un
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
