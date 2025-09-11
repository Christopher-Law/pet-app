<?php

namespace App\Factories;

use App\DTOs\PetDTO;
use App\Models\Pet;

class PetFactory
{
    public function create(PetDTO $petDTO): Pet
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

    public function createMultiple(array $petDTOs): array
    {
        return array_map(fn(PetDTO $dto) => $this->create($dto), $petDTOs);
    }

    public function createFromRequest(array $data): Pet
    {
        $petDTO = PetDTO::fromRequest($data);
        return $this->create($petDTO);
    }
}