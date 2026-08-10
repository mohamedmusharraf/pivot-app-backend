<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userParam = $this->route('user');
        $userId = is_object($userParam) ? $userParam->id : $userParam;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password'    => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
            'status'      => ['required', 'string', 'in:ready,not_ready'],
            'provider'    => ['required', 'string', 'in:email,google,apple'],
            'provider_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}