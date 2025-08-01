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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER', statusCode: 401)]
final class EditCommentController extends AbstractController
{
    #[Route('/edit/comment/{recipeId}/{commentId}', name: 'app_edit_comment')]
    public function index(string $recipeId, string $commentId, Request $request, EntityManagerInterface $em, CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();
        $comment = $commentRepository->findOneBy(['id' => $commentId]);
        if(!$comment) {
            throw $this->createNotFoundException(
                'Commentaire introuvable à l\'id : '. $commentId
            );
        }
        if($user && $comment->getUser() === $user) {
            // si l'utilisateur est connecter et que c'est son commentaire, il peut le modifier
            $form = $this->createForm(AddCommentForm::class, $comment);
            // pour lier le formulaire à la requête
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setUpdatedAt(new \DateTime());
                $comment->setStatus(200);
                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Modification du commentaire en cours de validation');
                return $this->redirectToRoute('app_recipe', ['id'=>$recipeId]);
            }
            return $this->render('edit_comment/index.html.twig', [
                'editCommentForm' => $form
            ]);
        } else {
            return $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        }
    }
}
