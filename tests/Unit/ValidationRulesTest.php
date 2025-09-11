<?php

use App\Enums\Breed;
use App\Enums\Type;
use App\Rules\IsValidBreed;
use App\Rules\IsValidSex;
use App\Rules\IsValidType;
use Illuminate\Translation\PotentiallyTranslatedString;

describe('Validation Rules', function () {
    describe('IsValidType Rule', function () {
        beforeEach(function () {
            $this->rule = new IsValidType();
        });

        it('passes validation for valid dog type', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('type', 'dog', $fail);
            expect(true)->toBeTrue(); // If we reach here, validation passed
        });

        it('passes validation for valid cat type', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('type', 'cat', $fail);
            expect(true)->toBeTrue();
        });

        it('fails validation for invalid type', function () {
            $failCalled = false;
            $failMessage = '';
            
            $fail = function ($message) use (&$failCalled, &$failMessage) {
                $failCalled = true;
                $failMessage = $message;
            };

            $this->rule->validate('type', 'invalid_type', $fail);
            
            expect($failCalled)->toBeTrue()
                ->and($failMessage)->toContain('The type must be one of the following:');
        });
    });

    describe('IsValidSex Rule', function () {
        beforeEach(function () {
            $this->rule = new IsValidSex();
        });

        it('passes validation for male', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('sex', 'male', $fail);
            expect(true)->toBeTrue();
        });

        it('passes validation for female', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('sex', 'female', $fail);
            expect(true)->toBeTrue();
        });

        it('fails validation for invalid sex', function () {
            $failCalled = false;
            $failMessage = '';
            
            $fail = function ($message) use (&$failCalled, &$failMessage) {
                $failCalled = true;
                $failMessage = $message;
            };

            $this->rule->validate('sex', 'other', $fail);
            
            expect($failCalled)->toBeTrue()
                ->and($failMessage)->toContain('The sex must be one of the following:');
        });
    });

    describe('IsValidBreed Rule', function () {
        beforeEach(function () {
            $this->rule = new IsValidBreed();
        });

        it('passes validation for valid dog breed', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('breed', Breed::GOLDEN_RETRIEVER->value, $fail);
            expect(true)->toBeTrue();
        });

        it('passes validation for valid cat breed', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('breed', Breed::PERSIAN->value, $fail);
            expect(true)->toBeTrue();
        });

        it('passes validation for special breeds', function () {
            $fail = function ($message) {
                $this->fail("Validation should have passed but failed with: {$message}");
            };

            $this->rule->validate('breed', Breed::DONT_KNOW->value, $fail);
            $this->rule->validate('breed', Breed::MIXED->value, $fail);
            expect(true)->toBeTrue();
        });

        it('fails validation for invalid breed', function () {
            $failCalled = false;
            $failMessage = '';
            
            $fail = function ($message) use (&$failCalled, &$failMessage) {
                $failCalled = true;
                $failMessage = $message;
            };

            $this->rule->validate('breed', 'invalid_breed', $fail);
            
            expect($failCalled)->toBeTrue()
                ->and($failMessage)->toContain('The breed must be one of the following:');
        });
    });
});

