<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AddCommentForm;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddCommentController extends AbstractController
{
    #[Route('/add/comment/add/{id}', name: 'app_add_comment')]
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
            $comment->setStatus(200);
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté avec success !');
            return $this->redirectToRoute('app_recipe', ['id'=>$id]);
        }

        return $this->render('add_comment/index.html.twig', [
            'controller_name' => 'AddCommentController',
            'addCommentForm' => $form
        ]);
    }
}
