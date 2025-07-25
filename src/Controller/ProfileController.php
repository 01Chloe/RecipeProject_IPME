<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeFormFlow;
use App\Services\RecipeServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ProfileController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/profile', name: 'app_profile')]
    public function index(RecipeServices $recipeServices, RecipeFormFlow $flow): Response
    {
        return $recipeServices->handleRecipeForm($flow, new Recipe(), $this->getUser());
    }
}
