<?php

namespace App\Factories;

use App\DTOs\PetDTO;
use App\Models\Pet;

class PetFactory
{
    public function createFromRequest(array $data): Pet
    {
        $petDTO = PetDTO::fromRequest($data);
        $processedDTO = $this->processPetData($petDTO);
        
        return Pet::make($processedDTO->toModelArray());
    }

    public function createFromDTO(PetDTO $petDTO): Pet
    {
        $processedDTO = $this->processPetData($petDTO);
        
        return Pet::make($processedDTO->toModelArray());
    }

    private function processPetData(PetDTO $petDTO): PetDTO
    {
        // Only auto-detect if dangerous animal flag wasn't explicitly provided
        if (!$petDTO->explicitlySetDangerous && $petDTO->hasBreed()) {
            $isDangerous = $petDTO->breed->isDangerous();
            if ($isDangerous) {
                return $petDTO->withDangerousAnimal(true);
            }
        }

        return $petDTO;
    }

    public function createMultiple(array $petsData): array
    {
        return array_map(fn($data) => $this->createFromRequest($data), $petsData);
    }

    public function createMultipleFromDTOs(array $petDTOs): array
    {
        return array_map(fn(PetDTO $dto) => $this->createFromDTO($dto), $petDTOs);
    }
}