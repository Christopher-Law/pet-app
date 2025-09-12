<?php

use App\Enums\Breed;
use App\Events\DangerousPetRegistered;
use App\Events\PetCreated;
use App\Models\Pet;
use App\Observers\PetObserver;
use Illuminate\Support\Facades\Event;

describe('PetObserver', function () {
    beforeEach(function () {
        $this->observer = new PetObserver;
        Event::fake();
    });

    describe('created method', function () {
        it('fires PetCreated event for any new pet', function () {
            $pet = Pet::factory()->make([
                'name' => 'Safe Pet',
                'breed' => Breed::GOLDEN_RETRIEVER,
                'is_dangerous_animal' => false,
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class, function ($event) use ($pet) {
                return $event->pet->name === $pet->name;
            });
        });

        it('fires DangerousPetRegistered event for dangerous breed', function () {
            $pet = Pet::factory()->make([
                'name' => 'Dangerous Pet',
                'breed' => Breed::PITBULL,
                'is_dangerous_animal' => false, // Even if flag is false, breed makes it dangerous
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class);
            Event::assertDispatched(DangerousPetRegistered::class, function ($event) use ($pet) {
                return $event->pet->name === $pet->name;
            });
        });

        it('fires DangerousPetRegistered event for pet marked as dangerous', function () {
            $pet = Pet::factory()->make([
                'name' => 'Aggressive Pet',
                'breed' => Breed::GOLDEN_RETRIEVER, // Safe breed
                'is_dangerous_animal' => true, // But marked as dangerous
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class);
            Event::assertDispatched(DangerousPetRegistered::class, function ($event) use ($pet) {
                return $event->pet->name === $pet->name;
            });
        });

        it('fires DangerousPetRegistered for mastiff breed', function () {
            $pet = Pet::factory()->make([
                'name' => 'Big Dog',
                'breed' => Breed::MASTIFF,
                'is_dangerous_animal' => false,
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(DangerousPetRegistered::class);
        });

        it('does not fire DangerousPetRegistered for safe pets', function () {
            $pet = Pet::factory()->make([
                'name' => 'Safe Pet',
                'breed' => Breed::GOLDEN_RETRIEVER,
                'is_dangerous_animal' => false,
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class);
            Event::assertNotDispatched(DangerousPetRegistered::class);
        });

        it('handles pets without breed gracefully', function () {
            $pet = Pet::factory()->make([
                'name' => 'Mystery Pet',
                'breed' => null,
                'is_dangerous_animal' => false,
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class);
            Event::assertNotDispatched(DangerousPetRegistered::class);
        });

        it('fires both events when dangerous breed and dangerous flag are set', function () {
            $pet = Pet::factory()->make([
                'name' => 'Very Dangerous Pet',
                'breed' => Breed::PITBULL,
                'is_dangerous_animal' => true,
            ]);

            $this->observer->created($pet);

            Event::assertDispatched(PetCreated::class);
            Event::assertDispatched(DangerousPetRegistered::class);

            // Verify only one DangerousPetRegistered event is fired (not double)
            Event::assertDispatchedTimes(DangerousPetRegistered::class, 1);
        });
    });

    describe('isDangerousPet method', function () {
        it('returns true for dangerous breed', function () {
            $pet = Pet::factory()->make([
                'breed' => Breed::PITBULL,
                'is_dangerous_animal' => false,
            ]);

            $reflection = new ReflectionClass($this->observer);
            $method = $reflection->getMethod('isDangerousPet');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->observer, [$pet]);

            expect($result)->toBeTrue();
        });

        it('returns true when marked as dangerous animal', function () {
            $pet = Pet::factory()->make([
                'breed' => Breed::GOLDEN_RETRIEVER,
                'is_dangerous_animal' => true,
            ]);

            $reflection = new ReflectionClass($this->observer);
            $method = $reflection->getMethod('isDangerousPet');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->observer, [$pet]);

            expect($result)->toBeTrue();
        });

        it('returns false for safe pet', function () {
            $pet = Pet::factory()->make([
                'breed' => Breed::GOLDEN_RETRIEVER,
                'is_dangerous_animal' => false,
            ]);

            $reflection = new ReflectionClass($this->observer);
            $method = $reflection->getMethod('isDangerousPet');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->observer, [$pet]);

            expect($result)->toBeFalse();
        });
    });
});
