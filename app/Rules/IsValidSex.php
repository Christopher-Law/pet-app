<?php

namespace App\Rules;

use App\Enums\Sex;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsValidSex implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validValues = array_column(Sex::cases(), 'value');

        if (! in_array($value, $validValues)) {
            $fail('The sex must be one of the following: '.implode(', ', $validValues));
        }
    }
}
