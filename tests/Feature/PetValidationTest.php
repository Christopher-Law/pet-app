<?php

use App\Enums\Breed;
use App\Enums\Type;

describe('Pet Request Validation', function () {
    it('validates complete pet creation request', function () {
        $validData = [
            'name' => 'Test Pet',
            'type' => Type::DOG->value,
            'breed' => Breed::GOLDEN_RETRIEVER->value,
            'date_of_birth' => '2020-01-01',
            'sex' => 'male',
            'is_dangerous_animal' => false,
        ];

        $response = $this->withoutMiddleware()->post('/pets', $validData);
        $response->assertStatus(302); // Successful redirect
    });

    it('rejects request with invalid data', function () {
        $invalidData = [
            'name' => '', // Required field empty
            'type' => 'invalid_type',
            'breed' => 'invalid_breed',
            'date_of_birth' => 'not-a-date',
            'sex' => 'invalid_sex',
            'is_dangerous_animal' => 'not_boolean',
        ];

        $response = $this->withoutMiddleware()->post('/pets', $invalidData);
        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'name',
                'type',
                'breed',
                'date_of_birth',
                'sex',
                'is_dangerous_animal',
            ]);
    });

    it('accepts minimal valid data', function () {
        $minimalData = [
            'name' => 'Minimal Pet',
            'is_dangerous_animal' => false,
        ];

        $response = $this->withoutMiddleware()->post('/pets', $minimalData);
        $response->assertStatus(302); // Should redirect successfully
    });
});
