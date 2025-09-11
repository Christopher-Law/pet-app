<?php

namespace App\Http\Requests;

use App\Rules\IsValidType;
use App\Rules\IsValidSex;
use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We can't actually use a Gate check here because we dont have a concept of auth users. 
        // Normally we would do something like this:
        // return Gate::allows('create', Pet::class);
        
        return true;
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
            'type' => ['nullable', new IsValidType()],
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'sex' => ['nullable', new IsValidSex()],
            'is_dangerous_animal' => 'required|boolean',
        ];
    }
}
