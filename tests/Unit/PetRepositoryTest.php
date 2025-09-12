<?php

use App\Enums\Breed;
use App\Models\Pet;
use App\Repositories\PetRepository;

describe('PetRepository', function () {
    beforeEach(function () {
        $this->repository = app(PetRepository::class);
    });

    describe('getAll method', function () {
        it('returns all pets', function () {
            Pet::factory()->count(3)->create();

            $pets = $this->repository->getAll();

            expect($pets)->toHaveCount(3);
        });

        it('returns empty collection when no pets exist', function () {
            $pets = $this->repository->getAll();

            expect($pets)->toHaveCount(0);
        });
    });

    describe('getLatest method', function () {
        it('returns pets in latest order', function () {
            $firstPet = Pet::factory()->create(['name' => 'First']);
            sleep(1);
            $secondPet = Pet::factory()->create(['name' => 'Second']);

            $pets = $this->repository->getLatest();

            expect($pets->first()->name)->toBe('Second')
                ->and($pets->last()->name)->toBe('First');
        });
    });

    describe('findById method', function () {
        it('finds pet by id', function () {
            $pet = Pet::factory()->create(['name' => 'Findable']);

            $foundPet = $this->repository->findById($pet->id);

            expect($foundPet)->not->toBeNull()
                ->and($foundPet->name)->toBe('Findable');
        });

        it('returns null for non-existent id', function () {
            $foundPet = $this->repository->findById('non-existent-id');

            expect($foundPet)->toBeNull();
        });
    });

    describe('create method', function () {
        it('creates a new pet', function () {
            $data = [
                'name' => 'New Pet',
                'type' => 'dog',
                'breed' => Breed::GOLDEN_RETRIEVER,
                'is_dangerous_animal' => false,
            ];

            $pet = $this->repository->create($data);

            expect($pet->name)->toBe('New Pet')
                ->and($pet->type)->toBe('dog')
                ->and($pet->breed)->toBe(Breed::GOLDEN_RETRIEVER)
                ->and($pet->is_dangerous_animal)->toBeFalse();
        });
    });

    describe('update method', function () {
        it('updates an existing pet', function () {
            $pet = Pet::factory()->create(['name' => 'Original Name']);
            $updateData = ['name' => 'Updated Name'];

            $result = $this->repository->update($pet, $updateData);

            expect($result)->toBeTrue()
                ->and($pet->fresh()->name)->toBe('Updated Name');
        });
    });

    describe('delete method', function () {
        it('soft deletes a pet', function () {
            $pet = Pet::factory()->create();

            $result = $this->repository->delete($pet);

            expect($result)->toBeTrue()
                ->and(Pet::find($pet->id))->toBeNull() // Can't find with regular query
                ->and(Pet::withTrashed()->find($pet->id))->not->toBeNull(); // But exists in trash
        });
    });

    describe('getDangerousPets method', function () {
        it('returns only dangerous pets', function () {
            Pet::factory()->create(['name' => 'Safe Pet', 'is_dangerous_animal' => false]);
            Pet::factory()->create(['name' => 'Dangerous Pet', 'is_dangerous_animal' => true]);

            $dangerousPets = $this->repository->getDangerousPets();

            expect($dangerousPets)->toHaveCount(1)
                ->and($dangerousPets->first()->name)->toBe('Dangerous Pet');
        });

        it('returns empty collection when no dangerous pets exist', function () {
            Pet::factory()->create(['is_dangerous_animal' => false]);

            $dangerousPets = $this->repository->getDangerousPets();

            expect($dangerousPets)->toHaveCount(0);
        });
    });

    describe('getPetsByType method', function () {
        it('returns pets of specified type', function () {
            Pet::factory()->create(['name' => 'Dog Pet', 'type' => 'dog']);
            Pet::factory()->create(['name' => 'Cat Pet', 'type' => 'cat']);

            $dogs = $this->repository->getPetsByType('dog');

            expect($dogs)->toHaveCount(1)
                ->and($dogs->first()->name)->toBe('Dog Pet');
        });

        it('returns empty collection when no pets of type exist', function () {
            Pet::factory()->create(['type' => 'dog']);

            $birds = $this->repository->getPetsByType('bird');

            expect($birds)->toHaveCount(0);
        });
    });
});
