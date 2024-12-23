<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LiteratureVariantUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required_without_all:description,language,file|string',
            'description' => 'required_without_all:title,language,file|string',
            'language' => 'required_without_all:title,description,file|string',
            'file' => 'required_without_all:title,description,language|file',
        ];
    }
}
