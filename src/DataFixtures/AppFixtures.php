<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\LikeFactory;
use App\Factory\RecipeFactory;
use App\Factory\StepFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        UserFactory::createMany(100);

        CategoryFactory::createOne(['label' => 'Chien']);
        CategoryFactory::createOne(['label' => 'Chat']);
        CategoryFactory::createOne(['label' => 'Lapin']);
        CategoryFactory::createOne(['label' => 'Piegon']);
        CategoryFactory::createOne(['label' => 'Tortue']);

        RecipeFactory::createMany(80);

        CommentFactory::createMany(68);

        LikeFactory::createMany(137);

        StepFactory::createMany(388);

        $manager->flush();
    }
}
