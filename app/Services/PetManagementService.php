<?php

namespace App\Services;

use App\Commands\CreatePetCommand;
use App\DTOs\PetDTO;
use App\Factories\PetFactory;
use App\Models\Pet;
use App\Repositories\Contracts\PetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PetManagementService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private PetFactory $petFactory,
        private CommandInvoker $commandInvoker
    ) {}

    public function getAllPets(): Collection
    {
        return $this->petRepository->getLatest();
    }

    public function createPet(PetDTO $petDTO): Pet
    {
        $command = new CreatePetCommand(
            $petDTO,
            $this->petRepository,
            $this->petFactory
        );

        return $this->commandInvoker->execute($command);
    }

    public function findPetById(string $id): ?Pet
    {
        return $this->petRepository->findById($id);
    }

    public function getDangerousPets(): Collection
    {
        return $this->petRepository->getDangerousPets();
    }

    public function getPetsByType(string $type): Collection
    {
        return $this->petRepository->getPetsByType($type);
    }

    public function getPetStatistics(): array
    {
        $allPets = $this->petRepository->getAll();
        $dangerousPets = $this->getDangerousPets();

        return [
            'total_pets' => $allPets->count(),
            'dangerous_pets' => $dangerousPets->count(),
            'dogs' => $this->getPetsByType('dog')->count(),
            'cats' => $this->getPetsByType('cat')->count(),
            'dangerous_percentage' => $allPets->count() > 0 
                ? round(($dangerousPets->count() / $allPets->count()) * 100, 1)
                : 0
        ];
    }
}