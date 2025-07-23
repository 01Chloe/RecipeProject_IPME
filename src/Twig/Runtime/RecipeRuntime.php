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
        private LikeRepository $likeRepository
    )
    {
    }

    public function hasLike(Recipe $recipe): Like|null
    {
        $user = $this->security->getUser();
        $like = $this->likeRepository->findOneBy(['recipe' => $recipe, 'user' => $user]);

        return $like;
    }
}
