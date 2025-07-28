<?php

namespace App\Controller;

use App\Enum\CommentStatusEnum;
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
        $user = $this->getUser();
        $comment = $commentRepository->findOneBy(['id' => $commentId]);
        if (!$comment) {
            throw $this->createNotFoundException(
                'Commentaire introuvable à l\'id : ' . $commentId
            );
        } elseif($user && $comment->getUser() === $user) {
            $comment->setStatus(CommentStatusEnum::COMMENT_STATUS_DELETE);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès !');
            return $this->redirectToRoute('app_recipe', ['id' => $recipeId]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
