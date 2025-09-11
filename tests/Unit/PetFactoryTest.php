<?php

use App\Factories\PetFactory;
use App\Models\Pet;
use App\Enums\Breed;

describe('PetFactory', function () {
    beforeEach(function () {
        $this->factory = new PetFactory();
    });

    describe('createFromRequest method', function () {
        it('creates a pet with basic data', function () {
            $data = [
                'name' => 'Buddy',
                'type' => 'dog',
                'breed' => 'Golden Retriever',
                'is_dangerous_animal' => false,
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet)->toBeInstanceOf(Pet::class)
                ->and($pet->name)->toBe('Buddy')
                ->and($pet->type)->toBe('dog')
                ->and($pet->breed)->toBe(Breed::GOLDEN_RETRIEVER)
                ->and($pet->is_dangerous_animal)->toBeFalse();
        });

        it('automatically detects dangerous breeds and sets dangerous flag', function () {
            $data = [
                'name' => 'Rex',
                'type' => 'dog',
                'breed' => 'Pitbull',
                // Note: no is_dangerous_animal field provided
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->breed)->toBe(Breed::PITBULL)
                ->and($pet->is_dangerous_animal)->toBeTrue();
        });

        it('automatically detects mastiff as dangerous breed', function () {
            $data = [
                'name' => 'Tank',
                'type' => 'dog', 
                'breed' => 'Mastiff',
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->breed)->toBe(Breed::MASTIFF)
                ->and($pet->is_dangerous_animal)->toBeTrue();
        });

        it('does not override explicit dangerous animal setting', function () {
            $data = [
                'name' => 'Gentle',
                'type' => 'dog',
                'breed' => 'Pitbull',
                'is_dangerous_animal' => false, // Explicitly set to false
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->breed)->toBe(Breed::PITBULL)
                ->and($pet->is_dangerous_animal)->toBeFalse(); // Should respect explicit setting
        });

        it('handles non-dangerous breeds correctly', function () {
            $data = [
                'name' => 'Fluffy',
                'type' => 'dog',
                'breed' => 'Golden Retriever',
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->breed)->toBe(Breed::GOLDEN_RETRIEVER)
                ->and($pet->is_dangerous_animal)->toBeFalse();
        });

        it('handles missing breed gracefully', function () {
            $data = [
                'name' => 'Mystery',
                'type' => 'cat',
                // No breed specified
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->name)->toBe('Mystery')
                ->and($pet->breed)->toBeNull()
                ->and($pet->is_dangerous_animal)->toBeFalse();
        });

        it('ensures boolean type for is_dangerous_animal', function () {
            $data = [
                'name' => 'Test',
                'is_dangerous_animal' => 'true', // String instead of boolean
            ];

            $pet = $this->factory->createFromRequest($data);

            expect($pet->is_dangerous_animal)->toBeTrue()
                ->and($pet->is_dangerous_animal)->toBe(true); // Ensure it's actually boolean
        });
    });

    describe('createMultiple method', function () {
        it('creates multiple pets from array of data', function () {
            $petsData = [
                [
                    'name' => 'Pet One',
                    'type' => 'dog',
                    'breed' => 'Beagle',
                    'is_dangerous_animal' => false,
                ],
                [
                    'name' => 'Pet Two',
                    'type' => 'cat',
                    'breed' => 'Persian',
                    'is_dangerous_animal' => false,
                ],
                [
                    'name' => 'Dangerous Pet',
                    'type' => 'dog',
                    'breed' => 'Pitbull',
                    // Should auto-detect dangerous
                ],
            ];

            $pets = $this->factory->createMultiple($petsData);

            expect($pets)->toHaveCount(3)
                ->and($pets[0]->name)->toBe('Pet One')
                ->and($pets[0]->breed)->toBe(Breed::BEAGLE)
                ->and($pets[0]->is_dangerous_animal)->toBeFalse()
                
                ->and($pets[1]->name)->toBe('Pet Two')
                ->and($pets[1]->breed)->toBe(Breed::PERSIAN)
                ->and($pets[1]->is_dangerous_animal)->toBeFalse()
                
                ->and($pets[2]->name)->toBe('Dangerous Pet')
                ->and($pets[2]->breed)->toBe(Breed::PITBULL)
                ->and($pets[2]->is_dangerous_animal)->toBeTrue(); // Auto-detected
        });

        it('returns empty array for empty input', function () {
            $pets = $this->factory->createMultiple([]);

            expect($pets)->toBeArray()
                ->and($pets)->toHaveCount(0);
        });
    });
});