<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $from = '/'.ltrim((string) $this->input('from_path'), '/');
        $to = (string) $this->input('to_path');

        if ($to !== '' && ! str_starts_with($to, 'http://') && ! str_starts_with($to, 'https://')) {
            $to = '/'.ltrim($to, '/');
        }

        $this->merge([
            'from_path' => $from === '//' ? '/' : $from,
            'to_path' => $to,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'from_path' => ['required', 'string', 'max:255', 'starts_with:/', Rule::unique('redirects', 'from_path')],
            'to_path' => ['required', 'string', 'max:2048'],
            'status' => ['required', 'integer', Rule::in([301, 302])],
        ];
    }
}
