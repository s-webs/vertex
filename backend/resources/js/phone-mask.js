export function formatPhoneMask(value) {
  let digits = String(value ?? '').replace(/\D/g, '');

  if (digits.startsWith('8')) {
    digits = `7${digits.slice(1)}`;
  }

  if (digits.startsWith('7')) {
    digits = digits.slice(1);
  }

  digits = digits.slice(0, 10);

  let result = '+7';

  if (digits.length === 0) {
    return result;
  }

  result += ` (${digits.slice(0, 3)}`;

  if (digits.length >= 3) {
    result += ')';
  }

  if (digits.length > 3) {
    result += ` ${digits.slice(3, 6)}`;
  }

  if (digits.length > 6) {
    result += `-${digits.slice(6, 8)}`;
  }

  if (digits.length > 8) {
    result += `-${digits.slice(8, 10)}`;
  }

  return result;
}

export function isCompletePhoneMask(value) {
  return /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/.test(String(value ?? ''));
}

export function bindPhoneMasks(root = document) {
  root.querySelectorAll('[data-phone-mask]').forEach((input) => {
    if (input.dataset.phoneMaskBound === '1') {
      return;
    }

    input.dataset.phoneMaskBound = '1';
    input.setAttribute('inputmode', 'tel');
    input.setAttribute('autocomplete', input.getAttribute('autocomplete') || 'tel');
    input.setAttribute('maxlength', '18');
    input.setAttribute('placeholder', input.getAttribute('placeholder') || '+7 (___) ___-__-__');
    input.setAttribute('pattern', '\\+7 \\(\\d{3}\\) \\d{3}-\\d{2}-\\d{2}');

    if (input.value.trim() !== '') {
      input.value = formatPhoneMask(input.value);
    }

    input.addEventListener('focus', () => {
      if (input.value.trim() === '') {
        input.value = '+7 (';
      }
    });

    input.addEventListener('input', () => {
      const formatted = formatPhoneMask(input.value);
      input.value = formatted === '+7' ? '+7 (' : formatted;
    });

    input.addEventListener('blur', () => {
      if (input.value === '+7' || input.value === '+7 (' || input.value === '+7 ()') {
        input.value = '';
        return;
      }

      if (input.value.trim() !== '' && !isCompletePhoneMask(input.value)) {
        input.setCustomValidity('Введите телефон полностью: +7 (___) ___-__-__');
      } else {
        input.setCustomValidity('');
      }
    });

    input.addEventListener('invalid', () => {
      if (input.validity.patternMismatch || input.validity.customError) {
        input.setCustomValidity('Введите телефон полностью: +7 (___) ___-__-__');
      }
    });
  });
}
