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
                'title' => 'required|string|max:255',
                'research_summary' => 'required|string',
                'research_full_text' => 'required|string',
                'files' => 'nullable|file|mimes:pdf,doc,docx', 
                'category' => 'required|string|max:255',
            ];
        }

        return [
            'title' => 'sometimes|string|max:255',
            'research_summary' => 'sometimes|string',
            'research_full_text' => 'sometimes|string',
            'files' => 'sometimes|nullable|file|mimes:pdf,doc,docx',
            'category' => 'sometimes|string|max:255',
        ];
    }
}