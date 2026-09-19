<?php

namespace App\Http\Requests\Admin;

use App\Support\SeoRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:120'],
            'year' => ['nullable', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'page_count' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['sometimes', 'boolean'],
            'cover' => ['nullable', 'image', 'max:8192'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            ...SeoRules::fields(),
        ];
    }
}
