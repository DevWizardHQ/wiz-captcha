<?php

namespace DevWizardHQ\Captcha\Rules;

use Closure;
use DevWizardHQ\Captcha\Captcha;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = session('wiz_captcha_key');

        if (! $key || ! app(Captcha::class)->verify($key, (string) $value)) {
            $fail('The CAPTCHA is incorrect or expired.');
        }
    }
}
