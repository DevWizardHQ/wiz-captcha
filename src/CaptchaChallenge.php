<?php

namespace DevWizardHQ\Captcha;

final class CaptchaChallenge
{
    public function __construct(
        public readonly string $id,
        public readonly string $question,
        public readonly string $answer,
        public readonly bool $caseSensitive = false,
    ) {}
}
