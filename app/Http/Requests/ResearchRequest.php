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
                'video_link' => 'nullable|url|max:2048',
                'video_type' => 'nullable|in:fun_facts,summary,both',
                'full_content' => 'nullable|string',
                'files' => 'nullable|file|mimes:pdf,doc,docx|max:512000',
                // 'category' => 'required|string|max:255',
            ];
        }

        return [
            'fun_facts' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string',
            'video_link' => 'sometimes|nullable|url|max:2048',
            'video_type' => 'sometimes|nullable|in:fun_facts,summary,both',
            'full_content' => 'sometimes|string',
            'files' => 'sometimes|nullable|file|mimes:pdf,doc,docx|max:512000',
            // 'category' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'fun_facts.string' => 'Fun facts must be valid text.',
            'fun_facts.max' => 'Fun facts must not exceed 255 characters.',
            'summary.string' => 'Summary must be valid text.',
            'video_link.url' => 'Video link must be a valid URL.',
            'video_link.max' => 'Video link must not exceed 2048 characters.',
            'video_type.in' => 'Video type must be fun_facts, summary, or both.',
            'full_content.string' => 'Full content must be valid text.',
            'files.file' => 'Files must be an uploaded file.',
            'files.mimes' => 'Files must be a PDF, DOC, or DOCX file.',
            'files.max' => 'Files must not be greater than 500 MB.',
        ];
    }
}
