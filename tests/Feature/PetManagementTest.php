<?php

use App\Enums\Breed;
use App\Models\Pet;

describe('Pet Management', function () {
    describe('GET /pets', function () {
        it('displays the pets index page', function () {
            Pet::factory()->count(3)->create();

            $response = $this->get('/pets');

            $response->assertStatus(200)
                ->assertViewIs('pets.index')
                ->assertViewHas('pets');
        });

        it('shows pets in latest order', function () {
            $olderPet = Pet::factory()->create(['name' => 'Older Pet']);
            sleep(1); // Ensure different timestamps
            $newerPet = Pet::factory()->create(['name' => 'Newer Pet']);

            $response = $this->get('/pets');

            $pets = $response->viewData('pets');
            expect($pets->first()->name)->toBe('Newer Pet')
                ->and($pets->last()->name)->toBe('Older Pet');
        });
    });

    describe('GET /pets/create', function () {
        it('displays the create pet form', function () {
            $response = $this->get('/pets/create');

            $response->assertStatus(200)
                ->assertViewIs('pets.create');
        });
    });

    describe('POST /pets', function () {
        it('creates a new pet with valid data', function () {
            $petData = [
                'name' => 'Buddy',
                'type' => 'dog',
                'breed' => Breed::GOLDEN_RETRIEVER->value,
                'date_of_birth' => '2020-01-01',
                'sex' => 'male',
                'is_dangerous_animal' => false,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302);
            
            $pet = Pet::where('name', 'Buddy')->first();
            expect($pet)->not->toBeNull()
                ->and($pet->name)->toBe('Buddy')
                ->and($pet->type)->toBe('dog')
                ->and($pet->breed)->toBe(Breed::GOLDEN_RETRIEVER);
        });

        it('redirects to pet show page after creation', function () {
            $petData = [
                'name' => 'Max',
                'type' => 'dog',
                'breed' => Breed::LABRADOR_RETRIEVER->value,
                'date_of_birth' => '2021-06-15',
                'sex' => 'male',
                'is_dangerous_animal' => false,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $pet = Pet::where('name', 'Max')->first();
            $response->assertRedirect(route('pets.show', $pet));
        });

        it('validates required name field', function () {
            $petData = [
                'type' => 'dog',
                'is_dangerous_animal' => false,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302)
                ->assertSessionHasErrors('name');
        });

        it('validates is_dangerous_animal field is required', function () {
            $petData = [
                'name' => 'Test Pet',
                'type' => 'dog',
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302)
                ->assertSessionHasErrors('is_dangerous_animal');
        });

        it('accepts valid optional fields', function () {
            $petData = [
                'name' => 'Fluffy',
                'type' => 'cat',
                'breed' => Breed::PERSIAN->value,
                'date_of_birth' => '2019-03-10',
                'sex' => 'female',
                'is_dangerous_animal' => false,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302);
            
            $pet = Pet::where('name', 'Fluffy')->first();
            expect($pet)->not->toBeNull()
                ->and($pet->type)->toBe('cat')
                ->and($pet->breed)->toBe(Breed::PERSIAN)
                ->and($pet->sex)->toBe('female');
        });

        it('can create pet without optional fields', function () {
            $petData = [
                'name' => 'Simple Pet',
                'is_dangerous_animal' => false,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302);
            
            $pet = Pet::where('name', 'Simple Pet')->first();
            expect($pet)->not->toBeNull()
                ->and($pet->type)->toBeNull()
                ->and($pet->breed)->toBeNull()
                ->and($pet->date_of_birth)->toBeNull()
                ->and($pet->sex)->toBeNull();
        });
    });

    describe('GET /pets/{pet}', function () {
        it('displays a specific pet', function () {
            $pet = Pet::factory()->create(['name' => 'Display Pet']);

            $response = $this->get("/pets/{$pet->id}");

            $response->assertStatus(200)
                ->assertViewIs('pets.show')
                ->assertViewHas('pet', $pet);
        });

        it('returns 404 for non-existent pet', function () {
            $response = $this->get('/pets/non-existent-id');

            $response->assertStatus(404);
        });

        it('does not display soft deleted pets', function () {
            $pet = Pet::factory()->create();
            $pet->delete();

            $response = $this->get("/pets/{$pet->id}");

            $response->assertStatus(404);
        });
    });

    describe('dangerous pets', function () {
        it('can create pets marked as dangerous', function () {
            $petData = [
                'name' => 'Dangerous Pet',
                'type' => 'dog',
                'breed' => Breed::PITBULL->value,
                'is_dangerous_animal' => true,
            ];

            $response = $this->withoutMiddleware()->post('/pets', $petData);

            $response->assertStatus(302);
            
            $pet = Pet::where('name', 'Dangerous Pet')->first();
            expect($pet)->not->toBeNull()
                ->and($pet->is_dangerous_animal)->toBeTrue()
                ->and($pet->isDangerousBreed())->toBeTrue();
        });
    });
});