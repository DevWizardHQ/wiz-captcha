<?php

namespace DevWizardHQ\Captcha\Rules;

use Closure;
use DevWizardHQ\Captcha\Captcha;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $keys = session('wiz_captcha_keys', []);
        $keys = is_array($keys) ? $keys : [];
        $fallbackKey = session('wiz_captcha_key');

        if (is_string($fallbackKey) && $fallbackKey !== '') {
            $keys[] = $fallbackKey;
        }

        $keys = array_values(array_unique(array_filter(
            $keys,
            static fn ($key) => is_string($key) && $key !== '',
        )));

        foreach ($keys as $key) {
            if (app(Captcha::class)->verify((string) $key, (string) $value)) {
                $remainingKeys = array_values(array_diff($keys, [$key]));

                session(['wiz_captcha_keys' => $remainingKeys]);

                if ($fallbackKey === $key) {
                    session()->forget('wiz_captcha_key');
                }

                return;
            }
        }

        $fail('The CAPTCHA is incorrect or expired.');
    }
}
