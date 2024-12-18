<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LitteratureVariantUploadRequest extends FormRequest
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
            'litterature_id' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'file' => ['required', 'file', 'mimes:pdf'],
            'language' => ['required', 'string']
        ];
    }
}
