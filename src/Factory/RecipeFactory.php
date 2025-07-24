<?php

namespace App\Factory;

use App\Entity\Recipe;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Recipe>
 */
final class RecipeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Recipe::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'category' => CategoryFactory::random(),
            'createdAt' => self::faker()->dateTimeBetween('-5 year', '-1 year'),
            'duration' => self::faker()->randomNumber(2, false),
            'imagePath' => 'https://picsum.photos/200/300',
            'level' => self::faker()->numberBetween(1, 3),
            'title' => self::faker()->word(),
            'updatedAt' => self::faker()->dateTimeBetween('-1 year', 'now'),
            'user' => UserFactory::random(),
            'status' => 300,
            'directives' => self::faker()->paragraph(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Recipe $recipe): void {})
        ;
    }
}
