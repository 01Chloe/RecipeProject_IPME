<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteCommentController extends AbstractController
{
    #[Route('/delete/comment/{recipeId}/{commentId}', name: 'app_delete_comment')]
    public function index(string $recipeId, string $commentId, EntityManagerInterface $em, CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $commentId]);

        if (!$comment) {
            throw $this->createNotFoundException(
                'No product found for id '.$commentId
            );
        } else {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Suppression du commentaire en cours de validation');
            return $this->redirectToRoute('app_recipe', ['id'=>$recipeId]);
        }
    }
}
