<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER', statusCode: 401)]
final class CheckHasLikeController extends AbstractController
{
    #[Route('/check/has/like/handle/{id}', name: 'app_check_has_like_handle', methods: ['POST'])]
    public function handleLike(string $id, RecipeRepository $recipeRepository, LikeRepository $likeRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        $like = $likeRepository->findOneBy(['recipe' => $id, 'user' => $user]);
        $action = '/login'; // rediriger sur la page de connxexion

        // Retourne 100 si l'utilisateur aime la recette et 200 sinon
        // Si l'utilisateur aime la recette on retire son like, sinon on l'ajout
        /** @var User $user */
        if ($user) {
            if ($like) {
                $em->remove($like);
                $em->flush();
                $action = 100; // supprimer
            } else {
                $like = new Like();

                $like->setRecipe($recipe);
                $like->setUser($user);
                $like->setCreatedAt(new \DateTime());
                $em->persist($like);
                $em->flush();
                $action = 200; // ajouter
            }
        }
        return new JsonResponse($action);
    }
}
