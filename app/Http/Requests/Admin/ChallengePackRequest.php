<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChallengePackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_id'         => 'required|string|max:255',
            'user_id'        => 'nullable|integer|exists:users,id',
            'product_id'     => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'environment'    => 'nullable|string|max:255',
            'store'          => 'nullable|string|max:255',
            'type'           => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'total'          => 'required|integer|min:0',
            'remaining'      => 'required|integer|min:0',
            'status'         => 'required|string|in:used,unused',
        ];
    }
}
