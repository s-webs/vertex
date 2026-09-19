<?php

namespace App\Support;

class Phone
{
    public const MASK_PATTERN = '/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/';

    public const MASK_EXAMPLE = '+7 (700) 123-45-67';

    public const MASK_MESSAGE = 'Введите телефон полностью в формате +7 (___) ___-__-__.';

    /**
     * @return list<string>
     */
    public static function requiredRules(): array
    {
        return ['required', 'string', 'regex:'.self::MASK_PATTERN];
    }

    /**
     * @return list<string>
     */
    public static function optionalRules(): array
    {
        return ['nullable', 'string', 'regex:'.self::MASK_PATTERN];
    }
}
