<?php

use App\DTOs\PetDTO;
use App\Http\Controllers\PetController;
use App\Http\Requests\StorePetRequest;
use App\Models\Pet;
use App\Services\PetManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;

describe('PetController', function () {
    beforeEach(function () {
        $this->petManagementService = $this->mock(PetManagementService::class);
        $this->controller = new PetController($this->petManagementService);
    });

    describe('index method', function () {
        it('returns view with latest pets', function () {
            $pets = new Collection([
                Pet::factory()->make(['name' => 'Buddy']),
                Pet::factory()->make(['name' => 'Max']),
            ]);

            $this->petManagementService
                ->shouldReceive('getAllPets')
                ->once()
                ->andReturn($pets);

            $result = $this->controller->index();

            expect($result)->toBeInstanceOf(View::class);
        });

        it('calls getAllPets on service', function () {
            $this->petManagementService
                ->shouldReceive('getAllPets')
                ->once()
                ->andReturn(new Collection);

            $this->controller->index();
        });
    });

    describe('create method', function () {
        it('returns create view', function () {
            $result = $this->controller->create();

            expect($result)->toBeInstanceOf(View::class);
        });
    });

    describe('store method', function () {
        it('creates pet and redirects to show page', function () {
            $validatedData = [
                'name' => 'Buddy',
                'type' => 'dog',
                'breed' => 'Golden Retriever',
                'date_of_birth' => '2020-01-01',
                'sex' => 'male',
                'is_dangerous_animal' => false,
            ];

            $pet = Pet::factory()->make(['id' => '01234567890123456789012345']);

            $request = $this->mock(StorePetRequest::class);
            $request->shouldReceive('validated')
                ->once()
                ->andReturn($validatedData);

            $this->petManagementService
                ->shouldReceive('createPet')
                ->once()
                ->with(Mockery::type(PetDTO::class))
                ->andReturn($pet);

            $result = $this->controller->store($request);

            expect($result)->toBeInstanceOf(RedirectResponse::class);
        });
    });

    describe('show method', function () {
        it('returns view with pet', function () {
            $pet = Pet::factory()->make();

            $result = $this->controller->show($pet);

            expect($result)->toBeInstanceOf(View::class);
        });
    });
});
