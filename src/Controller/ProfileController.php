<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\AddRecipeForm;
use App\Form\RecipeFormFlow;
use App\Services\RecipeServices;
use Craue\FormFlowBundle\Form\FormFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(RecipeServices $recipeServices, RecipeFormFlow $flow): Response
    {
        return $this->render('profile/index.html.twig', $recipeServices->handleRecipeForm($flow, new Recipe(), $this->getUser()));
    }
}
