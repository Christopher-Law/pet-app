<?php

use App\Enums\Breed;
use App\Models\Pet;
use Carbon\Carbon;

describe('Pet Model', function () {
    beforeEach(function () {
        $this->pet = Pet::factory()->make([
            'name' => 'Buddy',
            'type' => 'dog',
            'breed' => Breed::GOLDEN_RETRIEVER,
            'date_of_birth' => Carbon::now()->subYears(2)->subMonths(3),
            'sex' => 'male',
            'is_dangerous_animal' => false,
        ]);
    });

    it('can be created with valid attributes', function () {
        $pet = Pet::factory()->create([
            'name' => 'Max',
            'type' => 'dog',
            'breed' => Breed::LABRADOR_RETRIEVER,
        ]);

        expect($pet->name)->toBe('Max')
            ->and($pet->type)->toBe('dog')
            ->and($pet->breed)->toBe(Breed::LABRADOR_RETRIEVER);
    });

    it('uses ULIDs for primary keys', function () {
        $pet = Pet::factory()->create();
        
        expect($pet->id)->toBeString()
            ->and(strlen($pet->id))->toBe(26);
    });

    it('uses soft deletes', function () {
        $pet = Pet::factory()->create();
        
        $pet->delete();
        
        expect($pet->deleted_at)->not->toBeNull()
            ->and(Pet::find($pet->id))->toBeNull()
            ->and(Pet::withTrashed()->find($pet->id))->not->toBeNull();
    });

    describe('isDangerousBreed method', function () {
        it('returns true for dangerous breeds', function () {
            $pet = Pet::factory()->make(['breed' => Breed::PITBULL]);
            
            expect($pet->isDangerousBreed())->toBeTrue();
        });

        it('returns false for non-dangerous breeds', function () {
            $pet = Pet::factory()->make(['breed' => Breed::GOLDEN_RETRIEVER]);
            
            expect($pet->isDangerousBreed())->toBeFalse();
        });

        it('returns false when breed is null', function () {
            $pet = Pet::factory()->make(['breed' => null]);
            
            expect($pet->isDangerousBreed())->toBeFalse();
        });
    });

    describe('age attribute', function () {
        it('calculates age correctly for pets over 1 year', function () {
            $pet = Pet::factory()->make([
                'date_of_birth' => Carbon::now()->subYears(2)->subMonths(3)
            ]);
            
            expect($pet->age)->toBe('2 years and 3 months old');
        });

        it('calculates age correctly for pets exactly 1 year old', function () {
            $pet = Pet::factory()->make([
                'date_of_birth' => Carbon::now()->subYear()
            ]);
            
            expect($pet->age)->toBe('1 year old');
        });

        it('calculates age correctly for pets under 1 year', function () {
            $pet = Pet::factory()->make([
                'date_of_birth' => Carbon::now()->subMonths(6)
            ]);
            
            expect($pet->age)->toBe('6 months old');
        });

        it('handles 1 month old pets', function () {
            $pet = Pet::factory()->make([
                'date_of_birth' => Carbon::now()->subMonth()
            ]);
            
            expect($pet->age)->toBe('1 month old');
        });

        it('returns null when date_of_birth is null', function () {
            $pet = Pet::factory()->make(['date_of_birth' => null]);
            
            expect($pet->age)->toBeNull();
        });
    });

    describe('formatted breed attribute', function () {
        it('returns breed label when breed is set', function () {
            $pet = Pet::factory()->make(['breed' => Breed::GOLDEN_RETRIEVER]);
            
            expect($pet->formatted_breed)->toBe('Golden Retriever');
        });

        it('returns "Not specified" when breed is null', function () {
            $pet = Pet::factory()->make(['breed' => null]);
            
            expect($pet->formatted_breed)->toBe('Not specified');
        });

        it('handles special breed labels', function () {
            $pet = Pet::factory()->make(['breed' => Breed::DONT_KNOW]);
            
            expect($pet->formatted_breed)->toBe("I don't know");
        });
    });

    describe('casts', function () {
        it('casts breed to Breed enum', function () {
            $pet = Pet::factory()->create(['breed' => Breed::PITBULL]);
            
            expect($pet->breed)->toBeInstanceOf(Breed::class)
                ->and($pet->breed)->toBe(Breed::PITBULL);
        });

        it('casts date_of_birth to Carbon instance', function () {
            $pet = Pet::factory()->create(['date_of_birth' => '2020-01-01']);
            
            expect($pet->date_of_birth)->toBeInstanceOf(Carbon::class);
        });

        it('casts is_dangerous_animal to boolean', function () {
            $pet = Pet::factory()->create(['is_dangerous_animal' => 1]);
            
            expect($pet->is_dangerous_animal)->toBeBool()
                ->and($pet->is_dangerous_animal)->toBeTrue();
        });
    });
});