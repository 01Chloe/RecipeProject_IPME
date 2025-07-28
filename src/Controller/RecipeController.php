<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recipe/{id}', name: 'app_recipe')]
    public function index(string $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findOneBy(["id" => $id]);

        if(!$recipe) {
            throw $this->createNotFoundException(
                'Recette introuvable à l\'id : '. $id
            );
        }

        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipe' => $recipe
        ]);
    }
}
