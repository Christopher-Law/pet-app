<?php

namespace App\Events;

use App\Models\Pet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PetCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pet $pet
    ) {}
}
