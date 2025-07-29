<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckHasLikeController extends AbstractController
{
    #[Route('/check/has/like/handle/{id}', name: 'app_check_has_like_handle', methods: ['POST'])]
    public function handleLike(string $id, RecipeRepository $recipeRepository, LikeRepository $likeRepository, EntityManagerInterface $em): JsonResponse
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        $user = $this->getUser();
        $like = $likeRepository->findOneBy(['recipe' => $id, 'user' => $user]);
        $action = '/login'; // nothing

        /** @var User $user */
        if ($user) {
            if ($like) {
                $em->remove($like);
                $em->flush();
                $action = 100; // remove
            } else {
                $like = new Like();

                $like->setRecipe($recipe);
                $like->setUser($this->getUser());
                $like->setCreatedAt(new \DateTime());
                $em->persist($like);
                $em->flush();
                $action = 200; // add
            }
        }
        return new JsonResponse($action);
    }
}
