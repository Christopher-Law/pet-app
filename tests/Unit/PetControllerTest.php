<?php

use App\Http\Controllers\PetController;
use App\Http\Requests\StorePetRequest;
use App\Models\Pet;
use App\Repositories\Contracts\PetRepositoryInterface;
use App\Factories\PetFactory;
use App\Services\CommandInvoker;
use App\Commands\CreatePetCommand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Mockery\MockInterface;

describe('PetController', function () {
    beforeEach(function () {
        $this->petRepository = $this->mock(PetRepositoryInterface::class);
        $this->petFactory = $this->mock(PetFactory::class);
        $this->commandInvoker = $this->mock(CommandInvoker::class);
        $this->controller = new PetController(
            $this->petRepository, 
            $this->petFactory, 
            $this->commandInvoker
        );
    });

    describe('index method', function () {
        it('returns view with latest pets', function () {
            $pets = new Collection([
                Pet::factory()->make(['name' => 'Buddy']),
                Pet::factory()->make(['name' => 'Max']),
            ]);

            $this->petRepository
                ->shouldReceive('getLatest')
                ->once()
                ->andReturn($pets);

            $result = $this->controller->index();

            expect($result)->toBeInstanceOf(View::class);
        });

        it('calls getLatest on repository', function () {
            $this->petRepository
                ->shouldReceive('getLatest')
                ->once()
                ->andReturn(new Collection());

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

            $this->commandInvoker
                ->shouldReceive('execute')
                ->once()
                ->with(Mockery::type(CreatePetCommand::class))
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