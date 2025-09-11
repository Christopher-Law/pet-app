<?php

namespace App\Services;

use App\Strategies\Validation\PetValidationStrategy;
use App\Strategies\Validation\DogValidationStrategy;
use App\Strategies\Validation\CatValidationStrategy;
use App\Enums\Type;

class ValidationStrategyResolver
{
    public function resolve(?string $petType): PetValidationStrategy
    {
        if (!$petType) {
            // Default to dog strategy if no type specified (stricter rules)
            return new DogValidationStrategy();
        }

        $type = Type::tryFrom($petType);
        
        return match($type) {
            Type::DOG => new DogValidationStrategy(),
            Type::CAT => new CatValidationStrategy(),
            null => new DogValidationStrategy(), // Fallback to stricter rules
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