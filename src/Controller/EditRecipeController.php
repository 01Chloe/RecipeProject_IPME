<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeFormFlow;
use App\Repository\RecipeRepository;
use App\Services\RecipeServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditRecipeController extends AbstractController
{
    #[Route('/edit/recipe/{id}', name: 'app_edit_recipe')]
    public function index(string $id, RecipeServices $recipeServices, RecipeFormFlow $flow, RecipeRepository $recipeRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        if($user) {
            return $recipeServices->handleRecipeFormAction($flow, $recipe, $user);
        } else {
            return  $this->redirectToRoute('app_login');
        }
//        return $this->render('edit_recipe/index.html.twig', [
//            'controller_name' => 'EditRecipeController',
//        ]);
    }
}
