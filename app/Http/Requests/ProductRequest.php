<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'max:150'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'description' => ['nullable', 'min:3'],
            'date_time' => ['required',  Rule::date()->format('Y-m-d H:i:s')],
            'images' => ['nullable', 'array'],
            'images.*' => ['required', File::image()->min('1kb')->max('2mb')],
            'old_images' => ['sometimes', 'array'],
            'old_images.*' => ['nullable', Rule::exists('images', 'id')]
        ];
    }
}
