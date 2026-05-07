<?php

namespace DevWizardHQ\Captcha\Generators;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ChallengeGenerator;

class NumberGenerator implements ChallengeGenerator
{
    public function generate(array $config): CaptchaChallenge
    {
        $length = (int) ($config['length'] ?? 5);

        $text = '';

        for ($i = 0; $i < $length; $i++) {
            $text .= (string) random_int(2, 9);
        }

        return new CaptchaChallenge(
            id: bin2hex(random_bytes(16)),
            question: $text,
            answer: $text,
        );
    }
}
