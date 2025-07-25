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
        // Inject dependencies if needed
    }

    public function getComments(Recipe $recipe): array
    {
        $comment = $this->commentRepository->findBy(['recipe'=>$recipe, 'status' => 300]);
        return $comment;
    }
}
