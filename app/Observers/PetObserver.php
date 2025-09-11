<?php

namespace App\Observers;

use App\Events\DangerousPetRegistered;
use App\Events\PetCreated;
use App\Models\Pet;

class PetObserver
{
    public function created(Pet $pet): void
    {
        event(new PetCreated($pet));

        if ($this->isDangerousPet($pet)) {
            event(new DangerousPetRegistered($pet));
        }
    }

    private function isDangerousPet(Pet $pet): bool
    {
        return $pet->isDangerousBreed() || $pet->is_dangerous_animal;
    }
}