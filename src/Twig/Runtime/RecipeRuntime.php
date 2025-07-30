<?php

namespace App\Twig\Runtime;

use App\Entity\Like;
use App\Entity\Recipe;
use App\Repository\LikeRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\RuntimeExtensionInterface;

class RecipeRuntime implements RuntimeExtensionInterface
{

    public function __construct(
        private Security $security,
        private LikeRepository $likeRepository,
        private RecipeRepository $recipeRepository
    )
    {
    }

    public function hasLike(Recipe $recipe): Like|null
    {
        $user = $this->security->getUser();
        return $this->likeRepository->findOneBy(['recipe' => $recipe, 'user' => $user]);
    }
    public function getMyRecipes(): array
    {
        $user = $this->security->getUser();
        return $this->recipeRepository->findBy(['user' => $user, 'status' => 300], ['createdAt' => "DESC"]);
    }
}
