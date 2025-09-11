<?php

use App\Services\PetManagementService;
use App\Repositories\Contracts\PetRepositoryInterface;
use App\Factories\PetFactory;
use App\Services\CommandInvoker;
use App\Commands\CreatePetCommand;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;

describe('PetManagementService', function () {
    beforeEach(function () {
        $this->petRepository = $this->mock(PetRepositoryInterface::class);
        $this->petFactory = $this->mock(PetFactory::class);
        $this->commandInvoker = $this->mock(CommandInvoker::class);
        
        $this->service = new PetManagementService(
            $this->petRepository,
            $this->petFactory,
            $this->commandInvoker
        );
    });

    describe('getAllPets method', function () {
        it('returns latest pets from repository', function () {
            $expectedPets = new Collection([
                Pet::factory()->make(['name' => 'Pet 1']),
                Pet::factory()->make(['name' => 'Pet 2']),
            ]);

            $this->petRepository
                ->shouldReceive('getLatest')
                ->once()
                ->andReturn($expectedPets);

            $result = $this->service->getAllPets();

            expect($result)->toBe($expectedPets);
        });
    });

    describe('createPet method', function () {
        it('creates pet using command pattern', function () {
            $petData = [
                'name' => 'Test Pet',
                'type' => 'dog',
                'is_dangerous_animal' => false,
            ];
            
            $expectedPet = Pet::factory()->make($petData);

            $this->commandInvoker
                ->shouldReceive('execute')
                ->once()
                ->with(Mockery::type(CreatePetCommand::class))
                ->andReturn($expectedPet);

            $result = $this->service->createPet($petData);

            expect($result)->toBe($expectedPet);
        });
    });

    describe('findPetById method', function () {
        it('finds pet by id through repository', function () {
            $petId = 'test-pet-id';
            $expectedPet = Pet::factory()->make(['id' => $petId]);

            $this->petRepository
                ->shouldReceive('findById')
                ->once()
                ->with($petId)
                ->andReturn($expectedPet);

            $result = $this->service->findPetById($petId);

            expect($result)->toBe($expectedPet);
        });

        it('returns null when pet not found', function () {
            $this->petRepository
                ->shouldReceive('findById')
                ->once()
                ->with('non-existent-id')
                ->andReturn(null);

            $result = $this->service->findPetById('non-existent-id');

            expect($result)->toBeNull();
        });
    });

    describe('getDangerousPets method', function () {
        it('returns dangerous pets from repository', function () {
            $dangerousPets = new Collection([
                Pet::factory()->make(['name' => 'Dangerous Pet', 'is_dangerous_animal' => true]),
            ]);

            $this->petRepository
                ->shouldReceive('getDangerousPets')
                ->once()
                ->andReturn($dangerousPets);

            $result = $this->service->getDangerousPets();

            expect($result)->toBe($dangerousPets);
        });
    });

    describe('getPetsByType method', function () {
        it('returns pets of specific type from repository', function () {
            $dogPets = new Collection([
                Pet::factory()->make(['type' => 'dog']),
                Pet::factory()->make(['type' => 'dog']),
            ]);

            $this->petRepository
                ->shouldReceive('getPetsByType')
                ->once()
                ->with('dog')
                ->andReturn($dogPets);

            $result = $this->service->getPetsByType('dog');

            expect($result)->toBe($dogPets);
        });
    });

    describe('getPetStatistics method', function () {
        it('calculates correct statistics', function () {
            // Setup mock data
            $allPets = new Collection([
                Pet::factory()->make(['type' => 'dog', 'is_dangerous_animal' => false]),
                Pet::factory()->make(['type' => 'dog', 'is_dangerous_animal' => true]),
                Pet::factory()->make(['type' => 'cat', 'is_dangerous_animal' => false]),
                Pet::factory()->make(['type' => 'cat', 'is_dangerous_animal' => false]),
            ]);

            $dangerousPets = new Collection([
                Pet::factory()->make(['is_dangerous_animal' => true]),
            ]);

            $dogPets = new Collection([
                Pet::factory()->make(['type' => 'dog']),
                Pet::factory()->make(['type' => 'dog']),
            ]);

            $catPets = new Collection([
                Pet::factory()->make(['type' => 'cat']),
                Pet::factory()->make(['type' => 'cat']),
            ]);

            // Setup expectations
            $this->petRepository->shouldReceive('getAll')->once()->andReturn($allPets);
            $this->petRepository->shouldReceive('getDangerousPets')->once()->andReturn($dangerousPets);
            $this->petRepository->shouldReceive('getPetsByType')->with('dog')->once()->andReturn($dogPets);
            $this->petRepository->shouldReceive('getPetsByType')->with('cat')->once()->andReturn($catPets);

            $result = $this->service->getPetStatistics();

            expect($result)->toBe([
                'total_pets' => 4,
                'dangerous_pets' => 1,
                'dogs' => 2,
                'cats' => 2,
                'dangerous_percentage' => 25.0, // 1 out of 4 = 25%
            ]);
        });

        it('handles zero pets gracefully', function () {
            $emptyCollection = new Collection([]);

            $this->petRepository->shouldReceive('getAll')->once()->andReturn($emptyCollection);
            $this->petRepository->shouldReceive('getDangerousPets')->once()->andReturn($emptyCollection);
            $this->petRepository->shouldReceive('getPetsByType')->with('dog')->once()->andReturn($emptyCollection);
            $this->petRepository->shouldReceive('getPetsByType')->with('cat')->once()->andReturn($emptyCollection);

            $result = $this->service->getPetStatistics();

            expect($result)->toBe([
                'total_pets' => 0,
                'dangerous_pets' => 0,
                'dogs' => 0,
                'cats' => 0,
                'dangerous_percentage' => 0,
            ]);
        });

        it('calculates percentage correctly with all dangerous pets', function () {
            $allPets = new Collection([
                Pet::factory()->make(['is_dangerous_animal' => true]),
                Pet::factory()->make(['is_dangerous_animal' => true]),
            ]);

            $dangerousPets = new Collection([
                Pet::factory()->make(['is_dangerous_animal' => true]),
                Pet::factory()->make(['is_dangerous_animal' => true]),
            ]);

            $dogPets = new Collection([Pet::factory()->make(['type' => 'dog'])]);
            $catPets = new Collection([Pet::factory()->make(['type' => 'cat'])]);

            $this->petRepository->shouldReceive('getAll')->once()->andReturn($allPets);
            $this->petRepository->shouldReceive('getDangerousPets')->once()->andReturn($dangerousPets);
            $this->petRepository->shouldReceive('getPetsByType')->with('dog')->once()->andReturn($dogPets);
            $this->petRepository->shouldReceive('getPetsByType')->with('cat')->once()->andReturn($catPets);

            $result = $this->service->getPetStatistics();

            expect($result['dangerous_percentage'])->toBe(100.0); // 2 out of 2 = 100%
        });
    });
});