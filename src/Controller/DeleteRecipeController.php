<?php

namespace App\Controller;

use App\Enum\RecipeStatusEnum;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER', statusCode: 401)]
final class DeleteRecipeController extends AbstractController
{
    #[Route('/delete/recipe/{id}', name: 'app_delete_recipe')]
    public function index(string $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $recipe = $recipeRepository->findOneBy(["id" => $id]);

        if(!$recipe) {
            throw $this->createNotFoundException(
                'Recette introuvable à l\'id : '. $id
            );
        } elseif ($user && $recipe->getUser() === $user) {
            $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_DELETE);
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette supprimée avec succès !');
            return $this->redirectToRoute('app_home');
        } else {
            return $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        }
    }
}
