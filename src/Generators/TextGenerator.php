<?php

namespace DevWizardHQ\Captcha\Generators;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ChallengeGenerator;

class TextGenerator implements ChallengeGenerator
{
    public function generate(array $config): CaptchaChallenge
    {
        $characters = str_split($config['characters'] ?? config('wiz-captcha.characters'));
        $length = (int) ($config['length'] ?? 6);

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
