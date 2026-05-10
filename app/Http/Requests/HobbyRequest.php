<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HobbyRequest extends FormRequest
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
        $hobbyId = $this->route('hobby')?->id;
        
        return [
            'name' => 'required|string|max:255|unique:hobbies,name' . ($hobbyId ? ",$hobbyId" : ''),
            'icon_url' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Hobby name is required.',
            'name.string' => 'Hobby name must be a valid string.',
            'name.max' => 'Hobby name must not exceed 255 characters.',
            'name.unique' => 'This hobby already exists.',
            'icon_url.string' => 'Icon URL must be a valid string.',
            'icon_url.max' => 'Icon URL must not exceed 255 characters.',
        ];
    }
}
