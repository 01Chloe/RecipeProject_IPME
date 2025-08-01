<?php

namespace App\Controller;

use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckCountLikeController extends AbstractController
{
    #[Route('/check/count/like/{id}', name: 'app_check_count_like')]
    public function index(string $id, LikeRepository $likeRepository): JsonResponse
    {
        // Recupère le nombre de like par recette
        $likeCount = $likeRepository->getLikeCount($id);
        return new JsonResponse($likeCount);
    }
}
