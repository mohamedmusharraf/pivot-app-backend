<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserHobbyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'hobby_ids'   => 'required|array|min:1',
            'hobby_ids.*' => 'exists:hobbies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'hobby_ids.required' => 'Please select at least one hobby.',
            'hobby_ids.array' => 'Hobbies must be sent as an array.',
            'hobby_ids.min' => 'Please select at least one hobby.',
            'hobby_ids.*.exists' => 'One or more selected hobbies are invalid.',
        ];
    }
}
