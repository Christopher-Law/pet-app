<?php

namespace App\Repositories\Contracts;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;

interface PetRepositoryInterface
{
    public function getAll(): Collection;
    
    public function getLatest(): Collection;
    
    public function findById(string $id): ?Pet;
    
    public function create(array $data): Pet;
    
    public function update(Pet $pet, array $data): bool;
    
    public function delete(Pet $pet): bool;
    
    public function getDangerousPets(): Collection;
    
    public function getPetsByType(string $type): Collection;
}