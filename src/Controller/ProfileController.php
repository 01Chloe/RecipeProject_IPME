<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\User;
use App\Form\RecipeFormFlow;
use App\Services\FileUploaderService;
use App\Services\RecipeServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[IsGranted('ROLE_USER', statusCode: 401)]
final class ProfileController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/profile', name: 'app_profile')]
    public function index(RecipeServices $recipeServices, RecipeFormFlow $flow, FileUploaderService $fileUploaderService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if($user) {
            return $recipeServices->handleRecipeFormAction($flow, new Recipe(), $user, $fileUploaderService);
        } else {
            return  $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        }
    }
}
