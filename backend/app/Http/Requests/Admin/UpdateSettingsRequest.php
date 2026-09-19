<?php

namespace App\Http\Requests\Admin;

use App\Support\Phone;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'company_name' => ['required', 'string', 'max:120'],
            'whatsapp_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phones' => ['nullable', 'array'],
            'phones.*.label' => ['nullable', 'string', 'max:80'],
            'phones.*.number' => Phone::optionalRules(),
            'map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'map_zoom' => ['nullable', 'integer', 'min:4', 'max:19'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'logo_horizontal' => ['nullable', 'image', 'max:4096'],
            'favicon' => ['nullable', 'image', 'max:1024'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phones.*.number.regex' => Phone::MASK_MESSAGE,
        ];
    }
}
