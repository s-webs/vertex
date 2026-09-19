<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use App\Support\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(strip_tags((string) $this->input('name', ''))),
            'message' => trim(strip_tags((string) $this->input('message', ''))),
            'website' => (string) $this->input('website', ''),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'website' => ['nullable', 'string', 'max:0'],
            'name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\p{M}\s\'\-\.]+$/u'],
            'phone' => Phone::requiredRules(),
            'service' => ['required', 'string', Rule::in(config('site.inquiry_services'))],
            'message' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => Phone::MASK_MESSAGE,
            'website.max' => 'Заявка отклонена.',
            'name.regex' => 'Имя может содержать только буквы, пробелы и дефис.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $this->validateFormTiming($validator);
            $this->validateMessageLinks($validator);
            $this->validateDuplicateSubmission($validator);
        });
    }

    private function validateFormTiming(Validator $validator): void
    {
        $loadedAt = $this->session()->get('contact_form_loaded_at');
        $minSeconds = (int) config('site.inquiry.min_seconds', 3);

        if (! is_numeric($loadedAt)) {
            $validator->errors()->add('form', 'Обновите страницу и отправьте заявку ещё раз.');

            return;
        }

        $elapsed = now()->getTimestamp() - (int) $loadedAt;

        if ($elapsed < $minSeconds) {
            $validator->errors()->add('form', 'Подождите несколько секунд перед отправкой.');
        }

        if ($elapsed > 7200) {
            $validator->errors()->add('form', 'Сессия формы устарела. Обновите страницу и попробуйте снова.');
        }
    }

    private function validateMessageLinks(Validator $validator): void
    {
        $message = (string) $this->input('message', '');
        $maxLinks = (int) config('site.inquiry.max_links', 2);
        $linkCount = preg_match_all('/https?:\/\/|www\./i', $message) ?: 0;

        if ($linkCount > $maxLinks) {
            $validator->errors()->add('message', 'В сообщении слишком много ссылок.');
        }
    }

    private function validateDuplicateSubmission(Validator $validator): void
    {
        $minutes = (int) config('site.inquiry.duplicate_minutes', 2);

        $exists = Inquiry::query()
            ->where('ip_address', $this->ip())
            ->where('phone', (string) $this->input('phone'))
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->exists();

        if ($exists) {
            $validator->errors()->add('phone', 'Заявка с этим номером уже отправлена. Подождите немного.');
        }
    }
}
