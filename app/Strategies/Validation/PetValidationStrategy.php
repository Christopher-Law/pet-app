<?php

namespace App\Strategies\Validation;

interface PetValidationStrategy
{
    public function getRules(): array;

    public function getMessages(): array;
}
