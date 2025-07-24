<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $recipesAndCategories = $paginator->paginate(
            $recipeRepository->findBy(['status' => 300], ['createdAt' => "DESC"]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'recipesAndCategories' => $recipesAndCategories
        ]);
    }
}
