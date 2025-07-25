<?php

namespace App\Controller\admin;

use App\Enum\CommentStatusEnum;
use App\Enum\RecipeStatusEnum;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['status' => 200], ['createdAt' => "DESC"]);
        dump($comments);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comment/add/{id}', name: 'app_admin_add_comment')]
    public function addCommemnt(string $id, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $id]);

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

        $comment->setStatus(CommentStatusEnum::COMMENT_STATUS_ERROR);
        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Commentaire supprimé avec success !');
        return $this->redirectToRoute('app_admin');
    }
}
