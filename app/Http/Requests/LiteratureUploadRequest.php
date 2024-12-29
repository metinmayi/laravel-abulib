<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiteratureUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'language' => 'required|string',
            'category' => 'required|string',
            'file' => 'required|file|mimes:pdf',
            'english-title' => 'required|string',
            'kurdish-title' => 'required|string',
            'swedish-title' => 'required|string',
            'arabic-title' => 'required|string',
        ];
    }
}
