<?php

namespace App\Controller;

use App\Enum\RecipeStatusEnum;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteRecipeController extends AbstractController
{
    #[Route('/delete/recipe/{id}', name: 'app_delete_recipe')]
    public function index(string $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $recipe = $recipeRepository->findOneBy(["id" => $id]);

        if(!$recipe) {
            throw $this->createNotFoundException(
                'Recette introuvable à l\'id : '. $id
            );
        } elseif ($this->getUser() && $recipe->getUser() === $this->getUser()) {
            $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_DELETE);
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette supprimée avec succès !');
            return $this->redirectToRoute('app_home');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
