<?php

namespace App\Services;

use App\Enums\Type;
use App\Strategies\Validation\CatValidationStrategy;
use App\Strategies\Validation\DefaultValidationStrategy;
use App\Strategies\Validation\DogValidationStrategy;
use App\Strategies\Validation\PetValidationStrategy;

class ValidationStrategyResolver
{
    public function resolve(?string $petType): PetValidationStrategy
    {
        if (! $petType) {
            // Use default strategy when no type specified
            return new DefaultValidationStrategy;
        }

        $type = Type::tryFrom($petType);

        return match ($type) {
            Type::DOG => new DogValidationStrategy,
            Type::CAT => new CatValidationStrategy,
            null => new DefaultValidationStrategy, // Fallback to default strategy
        };
    }

    public function getValidationRules(?string $petType): array
    {
        return $this->resolve($petType)->getRules();
    }

    public function getValidationMessages(?string $petType): array
    {
        return $this->resolve($petType)->getMessages();
    }
}
