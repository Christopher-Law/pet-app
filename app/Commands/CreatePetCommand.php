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
    public function __construct(
        private PetDTO $petDTO,
        private PetRepositoryInterface $petRepository,
        private PetFactory $petFactory
    ) {}

    public function execute(): Pet
    {
        return DB::transaction(function () {
            $pet = $this->petFactory->create($this->petDTO);
            
            return $this->petRepository->create($pet->toArray());
        });
    }

    public function getPetDTO(): PetDTO
    {
        return $this->petDTO;
    }
}