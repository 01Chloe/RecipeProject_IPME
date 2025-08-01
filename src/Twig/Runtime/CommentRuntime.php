<?php

namespace App\Twig\Runtime;

use App\Entity\Recipe;
use App\Repository\CommentRepository;
use Twig\Extension\RuntimeExtensionInterface;

class CommentRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private CommentRepository $commentRepository
    )
    {
    }

    public function getComments(Recipe $recipe): array
    {
        // retourne tous les commentaires d'une recette qui ont un status "validé" par ordre décroissant
        $comment = $this->commentRepository->findBy(['recipe'=>$recipe, 'status' => 300], ['createdAt' => 'DESC']);
        return $comment;
    }
}
