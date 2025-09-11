<?php

namespace App\Strategies\Validation;

use App\Rules\IsValidBreed;
use App\Rules\IsValidSex;

class DogValidationStrategy implements PetValidationStrategy
{
    public function getRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog',
            'breed' => ['nullable', new IsValidBreed()],
            'date_of_birth' => 'nullable|date|before:today',
            'sex' => ['nullable', new IsValidSex()],
            'is_dangerous_animal' => 'required|boolean',
        ];
    }

    public function getMessages(): array
    {
        return [
            'name.required' => 'Pet name is required.',
            'is_dangerous_animal.required' => 'Please specify if this pet requires special handling.',
        ];
    }
}