<?php

use App\Services\ValidationStrategyResolver;
use App\Strategies\Validation\CatValidationStrategy;
use App\Strategies\Validation\DefaultValidationStrategy;
use App\Strategies\Validation\DogValidationStrategy;

describe('ValidationStrategy', function () {
    beforeEach(function () {
        $this->resolver = new ValidationStrategyResolver;
    });

    describe('ValidationStrategyResolver', function () {
        it('resolves dog strategy for dog type', function () {
            $strategy = $this->resolver->resolve('dog');

            expect($strategy)->toBeInstanceOf(DogValidationStrategy::class);
        });

        it('resolves cat strategy for cat type', function () {
            $strategy = $this->resolver->resolve('cat');

            expect($strategy)->toBeInstanceOf(CatValidationStrategy::class);
        });

        it('resolves default strategy when no type provided', function () {
            $strategy = $this->resolver->resolve(null);

            expect($strategy)->toBeInstanceOf(DefaultValidationStrategy::class);
        });

        it('resolves default strategy for invalid type', function () {
            $strategy = $this->resolver->resolve('invalid-type');

            expect($strategy)->toBeInstanceOf(DefaultValidationStrategy::class);
        });

        it('gets validation rules for dog type', function () {
            $rules = $this->resolver->getValidationRules('dog');

            expect($rules)->toBeArray()
                ->and($rules['name'])->toBe('required|string|max:255')
                ->and($rules['type'])->toContain('required')
                ->and($rules['type'])->toContain('in:dog');
        });

        it('gets validation rules for cat type', function () {
            $rules = $this->resolver->getValidationRules('cat');

            expect($rules)->toBeArray()
                ->and($rules['name'])->toBe('required|string|max:255')
                ->and($rules['type'])->toContain('required')
                ->and($rules['type'])->toContain('in:cat');
        });

        it('gets default validation rules when no type provided', function () {
            $rules = $this->resolver->getValidationRules(null);

            expect($rules)->toBeArray()
                ->and($rules['name'])->toBe('required|string|max:255')
                ->and($rules['type'])->toContain('nullable'); // Type is optional for default
        });
    });

    describe('DogValidationStrategy', function () {
        beforeEach(function () {
            $this->strategy = new DogValidationStrategy;
        });

        it('requires dog type specifically', function () {
            $rules = $this->strategy->getRules();

            expect($rules['type'])->toContain('required')
                ->and($rules['type'])->toContain('in:dog');
        });

        it('has all required pet fields', function () {
            $rules = $this->strategy->getRules();

            expect($rules)->toHaveKeys([
                'name',
                'type',
                'breed',
                'date_of_birth',
                'sex',
                'is_dangerous_animal',
            ]);
        });

        it('provides helpful validation messages', function () {
            $messages = $this->strategy->getMessages();

            expect($messages)->toBeArray()
                ->and($messages['name.required'])->toBe('Pet name is required.')
                ->and($messages['is_dangerous_animal.required'])->toBe('Please specify if this pet requires special handling.');
        });
    });

    describe('CatValidationStrategy', function () {
        beforeEach(function () {
            $this->strategy = new CatValidationStrategy;
        });

        it('requires cat type specifically', function () {
            $rules = $this->strategy->getRules();

            expect($rules['type'])->toContain('required')
                ->and($rules['type'])->toContain('in:cat');
        });

        it('has same field structure as dog strategy', function () {
            $dogStrategy = new DogValidationStrategy;
            $catRules = $this->strategy->getRules();
            $dogRules = $dogStrategy->getRules();

            // Should have same field keys (same structure)
            expect(array_keys($catRules))->toBe(array_keys($dogRules));
        });

        it('provides helpful validation messages', function () {
            $messages = $this->strategy->getMessages();

            expect($messages)->toBeArray()
                ->and($messages)->toHaveKey('name.required')
                ->and($messages)->toHaveKey('is_dangerous_animal.required');
        });
    });

    describe('DefaultValidationStrategy', function () {
        beforeEach(function () {
            $this->strategy = new DefaultValidationStrategy;
        });

        it('makes type field nullable', function () {
            $rules = $this->strategy->getRules();

            expect($rules['type'])->toContain('nullable');
        });

        it('still requires name and dangerous animal flag', function () {
            $rules = $this->strategy->getRules();

            expect($rules['name'])->toBe('required|string|max:255')
                ->and($rules['is_dangerous_animal'])->toBe('required|boolean');
        });

        it('has all pet fields with flexible requirements', function () {
            $rules = $this->strategy->getRules();

            expect($rules)->toHaveKeys([
                'name',
                'type',
                'breed',
                'date_of_birth',
                'sex',
                'is_dangerous_animal',
            ]);

            // Most fields should be nullable except name and is_dangerous_animal
            expect($rules['breed'])->toContain('nullable')
                ->and($rules['date_of_birth'])->toContain('nullable')
                ->and($rules['sex'])->toContain('nullable');
        });

        it('provides appropriate validation messages', function () {
            $messages = $this->strategy->getMessages();

            expect($messages)->toBeArray()
                ->and($messages['name.required'])->toBe('Pet name is required.')
                ->and($messages['is_dangerous_animal.required'])->toBe('Please specify if this pet requires special handling.');
        });
    });
});
