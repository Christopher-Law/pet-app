<?php

namespace App\Commands;

use App\Commands\Contracts\CommandInterface;
use App\DTOs\PetDTO;
use App\Factories\PetFactory;
use App\Models\Pet;
use App\Repositories\Contracts\PetRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CreatePetCommand implements CommandInterface
{
    private PetDTO $petDTO;

    public function __construct(
        array|PetDTO $petData,
        private PetRepositoryInterface $petRepository,
        private PetFactory $petFactory
    ) {
        $this->petDTO = $petData instanceof PetDTO 
            ? $petData 
            : PetDTO::fromArray($petData);
    }

    public function execute(): Pet
    {
        return DB::transaction(function () {
            $pet = $this->petFactory->createFromDTO($this->petDTO);
            
            return $this->petRepository->create($pet->toArray());
        });
    }

    public function getPetDTO(): PetDTO
    {
        return $this->petDTO;
    }

    // Backward compatibility
    public function getPetData(): array
    {
        return $this->petDTO->toArray();
    }
}