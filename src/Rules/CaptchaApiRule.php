<?php

namespace DevWizardHQ\Captcha\Rules;

use Closure;
use DevWizardHQ\Captcha\Captcha;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaApiRule implements ValidationRule
{
    public function __construct(
        private readonly string $key,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! app(Captcha::class)->verify($this->key, (string) $value)) {
            $fail('The CAPTCHA is incorrect or expired.');
        }
    }
}
