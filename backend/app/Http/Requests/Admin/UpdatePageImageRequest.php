<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageImageRequest extends FormRequest
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
        $pages = array_keys(config('site.page_images'));

        return [
            'page_key' => ['required', 'string', Rule::in($pages)],
            'slot_key' => ['required', 'string'],
            'alt' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:8192'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $page = $this->input('page_key');
            $slot = $this->input('slot_key');

            if ($page && $slot && ! isset(config('site.page_images')[$page][$slot])) {
                $validator->errors()->add('slot_key', 'Неизвестный слот изображения.');
            }
        });
    }
}
