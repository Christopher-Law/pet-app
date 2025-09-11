<?php

namespace App\Http\Requests;

use App\Models\Pet;
use App\Enums\Sex;
use App\Enums\Type;
use App\Rules\IsValidType;
use App\Rules\IsValidSex;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Pet::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['nullable', IsValidType::class],
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'sex' => ['nullable', IsValidSex::class],
            'is_dangerous_animal' => 'required|boolean',
            'owner_id' => 'required|exists:users,id',
        ];
    }
}
