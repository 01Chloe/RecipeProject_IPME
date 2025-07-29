<?php

namespace App\Controller\admin;

use App\Enum\CommentStatusEnum;
use App\Enum\RecipeStatusEnum;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN', statusCode: 401)]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(CommentRepository $commentRepository, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findBy(['status' => 200], ['createdAt' => 'DESC']);
        $comments = $commentRepository->findBy(['status' => 200], ['createdAt' => "DESC"]);

        return $this->render('admin/index.html.twig', [
            'comments' => $comments,
            'recipes' => $recipes
        ]);
    }

    #[Route('/admin/comment/add/{id}', name: 'app_admin_add_comment')]
    public function addCommemnt(string $id, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $id]);

        if (!$comment) {
            throw $this->createNotFoundException(
                'Commentaire introuvable à l\'id : ' . $id
            );
        }

        $comment->setStatus(CommentStatusEnum::COMMENT_STATUS_VALIDATE);
        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Commentaire ajouté avec success !');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/comment/delete/{id}', name: 'app_admin_delete_comment')]
    public function deleteCommemnt(string $id, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $id]);
        if (!$comment) {
            throw $this->createNotFoundException(
                'Commentaire introuvable à l\'id : ' . $id
            );
        }

        $comment->setStatus(CommentStatusEnum::COMMENT_STATUS_DELETE);
        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Commentaire supprimé avec succès !');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/recipe/add/{id}', name: 'app_admin_add_recipe')]
    public function addRecipe(string $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        if (!$recipe) {
            throw $this->createNotFoundException(
                'Recette introuvable à l\'id : ' . $id
            );
        }

        $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_VALIDATE);
        $em->persist($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette ajoutée avec success !');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/recipe/delete/{id}', name: 'app_admin_delete_recipe')]
    public function deleteRecipe(string $id, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        if (!$recipe) {
            throw $this->createNotFoundException(
                'Recette introuvable à l\'id : ' . $id
            );
        }

        $recipe->setStatus(RecipeStatusEnum::RECIPE_STATUS_DELETE);
        $em->persist($recipe);
        $em->flush();

        $this->addFlash('success', 'Recette suppimée avec success !');
        return $this->redirectToRoute('app_admin');
    }
}
