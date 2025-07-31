<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeFormFlow;
use App\Repository\RecipeRepository;
use App\Services\FileUploaderService;
use App\Services\RecipeServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER', statusCode: 401)]
final class EditRecipeController extends AbstractController
{
    #[Route('/edit/recipe/{id}', name: 'app_edit_recipe')]
    public function index(string $id, RecipeServices $recipeServices, RecipeFormFlow $flow, RecipeRepository $recipeRepository, FileUploaderService $fileUploaderService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $recipe = $recipeRepository->findOneBy(['id' => $id, 'user' => $user]);
        if($user) {
            return $recipeServices->handleRecipeFormAction($flow, $recipe, $user, $fileUploaderService);
        } else {
            return  $this->redirectToRoute('app_login');
        }
    }
}
