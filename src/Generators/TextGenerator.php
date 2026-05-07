<?php

namespace DevWizardHQ\Captcha\Generators;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ChallengeGenerator;
use InvalidArgumentException;

class TextGenerator implements ChallengeGenerator
{
    public function generate(array $config): CaptchaChallenge
    {
        $characters = str_split((string) ($config['characters'] ?? config('wiz-captcha.characters')));
        $length = (int) ($config['length'] ?? 6);

        if ($characters === []) {
            throw new InvalidArgumentException('CAPTCHA characters cannot be empty.');
        }

        if ($length < 1) {
            throw new InvalidArgumentException('CAPTCHA length must be at least 1.');
        }

        $text = '';

        for ($i = 0; $i < $length; $i++) {
            $text .= $characters[random_int(0, count($characters) - 1)];
        }

        return new CaptchaChallenge(
            id: bin2hex(random_bytes(16)),
            question: $text,
            answer: $text,
            caseSensitive: (bool) config('wiz-captcha.case_sensitive', false),
        );
    }
}
