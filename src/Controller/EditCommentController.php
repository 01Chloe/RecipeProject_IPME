<?php

namespace App\Controller;

use App\Form\AddCommentForm;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EditCommentController extends AbstractController
{
    #[Route('/edit/comment/edit/{recipeId}/{commentId}', name: 'app_edit_comment')]
    public function index(string $recipeId, string $commentId, Request $request, EntityManagerInterface $em, RecipeRepository $recipeRepository, CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $commentId]);
        $form = $this->createForm(AddCommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUpdatedAt(new \DateTime());
            $comment->setStatus(200);
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire modifié avec success !');
            return $this->redirectToRoute('app_recipe', ['id'=>$recipeId]);
        }
        return $this->render('edit_comment/index.html.twig', [
            'controller_name' => 'EditCommentController',
            'editCommentForm' => $form
        ]);
    }
}
