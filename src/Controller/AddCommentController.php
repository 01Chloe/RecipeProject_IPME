<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Enum\CommentStatusEnum;
use App\Form\AddCommentForm;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddCommentController extends AbstractController
{
    #[Route('/add/comment/{id}', name: 'app_add_comment')]
    public function index(string $id, Request $request, EntityManagerInterface $em, RecipeRepository $recipeRepository): Response
    {
        $comment = new Comment();
        $recipe = $recipeRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(AddCommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setRecipe($recipe);
            $comment->setCreatedAt(new \DateTime());
            $comment->setStatus(CommentStatusEnum::COMMENT_STATUS_IN_VALIDATION);
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Ajout du commentaire en cours de validation');
            return $this->redirectToRoute('app_recipe', ['id'=>$id]);
        }

        return $this->render('add_comment/index.html.twig', [
            'controller_name' => 'AddCommentController',
            'addCommentForm' => $form
        ]);
    }
}
