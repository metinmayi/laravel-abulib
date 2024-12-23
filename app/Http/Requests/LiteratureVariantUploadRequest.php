<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiteratureVariantUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'file' => ['required', 'file', 'mimes:pdf'],
            'language' => ['required', 'string']
        ];
    }
}
