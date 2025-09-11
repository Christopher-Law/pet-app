<?php

namespace App\Http\Controllers;

use App\Commands\CreatePetCommand;
use App\Factories\PetFactory;
use App\Http\Requests\StorePetRequest;
use App\Models\Pet;
use App\Repositories\Contracts\PetRepositoryInterface;
use App\Services\CommandInvoker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PetController extends Controller
{
    public function __construct(
        protected PetRepositoryInterface $petRepository,
        protected PetFactory $petFactory,
        protected CommandInvoker $commandInvoker
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = $this->petRepository->getLatest();

        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request): RedirectResponse
    {
        $command = new CreatePetCommand(
            $request->validated(),
            $this->petRepository,
            $this->petFactory
        );

        $pet = $this->commandInvoker->execute($command);

        return redirect()->route('pets.show', $pet);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): View
    {
        return view('pets.show', compact('pet'));
    }

    
}
