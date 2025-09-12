<?php

namespace App\Http\Requests;

use App\Services\ValidationStrategyResolver;
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
        $resolver = app(ValidationStrategyResolver::class);

        return $resolver->getValidationRules($this->input('type'));
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        $resolver = app(ValidationStrategyResolver::class);

        return $resolver->getValidationMessages($this->input('type'));
    }
}
