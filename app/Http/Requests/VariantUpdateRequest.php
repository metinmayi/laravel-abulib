<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariantUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required_without_all:description,file|string|nullable',
            'description' => 'required_without_all:title,file|string|nullable',
            'file' => 'required_without_all:title,description|file|nullable',
        ];
    }
}
