<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'fun_facts' => 'nullable|string|max:255',
                'summary' => 'nullable|string',
                'full_content' => 'nullable|string',
                'files' => 'nullable|file|mimes:pdf,doc,docx', 
                // 'category' => 'required|string|max:255',
            ];
        }

        return [
            'fun_facts' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string',
            'full_content' => 'sometimes|string',
            'files' => 'sometimes|nullable|file|mimes:pdf,doc,docx',
            // 'category' => 'sometimes|string|max:255',
        ];
    }
}