<?php

namespace App\Http\Requests\Admin;

use App\Support\SeoRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeoRequest extends FormRequest
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
        return SeoRules::fields();
    }
}
