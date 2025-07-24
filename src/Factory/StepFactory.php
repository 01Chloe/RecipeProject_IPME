<?php

namespace App\Factory;

use App\Entity\Step;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Step>
 */
final class StepFactory extends PersistentProxyObjectFactory
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
        return Step::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'content' => self::faker()->paragraph(),
            'createdAt' => self::faker()->dateTimeBetween('-5 year', '-1 year'),
            'recipe' => RecipeFactory::random(),
            'title' => self::faker()->word(),
            'updatedAt' => self::faker()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Step $step): void {})
        ;
    }
}
