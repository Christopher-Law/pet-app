<?php

namespace App\Factories;

use App\Models\Pet;
use App\Enums\Breed;

class PetFactory
{
    public function createFromRequest(array $data): Pet
    {
        $petData = $this->processPetData($data);
        
        return Pet::make($petData);
    }

    private function processPetData(array $data): array
    {
        $processedData = $data;
        
        // Auto-detect dangerous animal status if not explicitly set
        if (!isset($processedData['is_dangerous_animal']) && isset($data['breed'])) {
            $breed = Breed::tryFrom($data['breed']);
            $processedData['is_dangerous_animal'] = $breed ? $breed->isDangerous() : false;
        }

        // Ensure boolean type
        $processedData['is_dangerous_animal'] = (bool) ($processedData['is_dangerous_animal'] ?? false);

        return $processedData;
    }

    public function createMultiple(array $petsData): array
    {
        return array_map(fn($data) => $this->createFromRequest($data), $petsData);
    }
}