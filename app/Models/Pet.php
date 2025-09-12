<?php

namespace App\Models;

use App\Enums\Breed;
use App\Observers\PetObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(PetObserver::class)]
class Pet extends Model
{
    use HasFactory;
    use HasTimestamps;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'breed',
        'date_of_birth',
        'sex',
        'is_dangerous_animal',
    ];

    protected $casts = [
        'breed' => Breed::class,
        'date_of_birth' => 'date',
        'is_dangerous_animal' => 'boolean',
    ];

    /**
     * Check if this pet's breed is considered dangerous
     */
    public function isDangerousBreed(): bool
    {
        return $this->breed?->isDangerous() ?? false;
    }

    /**
     * Get the pet's age in a human readable format
     */
    public function getAgeAttribute(): ?string
    {
        if (! $this->date_of_birth) {
            return null;
        }

        $age = $this->date_of_birth->age;
        $months = $this->date_of_birth->diffInMonths(now()) % 12;

        if ($age > 0) {
            $ageString = $age.' '.($age === 1 ? 'year' : 'years');
            if ($months > 0) {
                $ageString .= ' and '.$months.' '.($months === 1 ? 'month' : 'months');
            }
        } else {
            $ageString = $months.' '.($months === 1 ? 'month' : 'months');
        }

        return $ageString.' old';
    }

    /**
     * Get formatted breed name
     */
    public function getFormattedBreedAttribute(): string
    {
        if (! $this->breed) {
            return 'Not specified';
        }

        return $this->breed->getLabel();
    }
}
