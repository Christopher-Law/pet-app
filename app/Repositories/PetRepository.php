<?php

namespace App\Repositories;

use App\Models\Pet;
use App\Repositories\Contracts\PetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PetRepository implements PetRepositoryInterface
{
    public function __construct(
        protected Pet $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getLatest(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findById(string $id): ?Pet
    {
        return $this->model->find($id);
    }

    public function create(array $data): Pet
    {
        return $this->model->create($data);
    }

    public function update(Pet $pet, array $data): bool
    {
        return $pet->update($data);
    }

    public function delete(Pet $pet): bool
    {
        return $pet->delete();
    }

    public function getDangerousPets(): Collection
    {
        return $this->model->where('is_dangerous_animal', true)->get();
    }

    public function getPetsByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
}