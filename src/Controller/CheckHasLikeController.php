<?php

namespace App\Controller;

use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckHasLikeController extends AbstractController
{
    #[Route('/check/has/like/add/{id}', name: 'app_check_has_like_add')]
    public function add(int $id, EntityManagerInterface $em, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        $like = new Like();

        $like->setRecipe($recipe);
        dump($id);
        $like->setUser($this->getUser());
        $like->setCreatedAt(new \DateTime());
        $em->persist($like);
        $em->flush();
        $this->addFlash('success', 'Like ajouté !');
        return $this->redirectToRoute('app_recipe', ['id' => $id]);
    }

    #[Route('/check/has/like/remove/{id}', name: 'app_check_has_like_remove')]
    public function remove(int $id, EntityManagerInterface $em, LikeRepository $likeRepository): Response
    {
        $user = $this->getUser();
        $like = $likeRepository->findOneBy(['recipe' => $id, 'user' => $user]);
        if (!$like) {
            throw $this->createNotFoundException(
                'Recette introuvable avec l\'id : '.$id
            );
        } else {
            $em->remove($like);
            $em->flush();
            $this->addFlash('success', 'Like supprimer !');
            return $this->redirectToRoute('app_recipe', ['id' => $id]);
        }
    }
}
