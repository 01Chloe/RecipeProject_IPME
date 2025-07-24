<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\AddRecipeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(AddRecipeForm::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUser($this->getUser());
            $recipe->setCreatedAt(new \DateTime());
            $recipe->setStatus(200);
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', 'Recette ajoutée avec success !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'addForm' => $form
        ]);
    }
}
