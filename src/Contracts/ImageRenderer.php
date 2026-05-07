<?php

namespace DevWizardHQ\Captcha\Contracts;

use DevWizardHQ\Captcha\CaptchaChallenge;

interface ImageRenderer
{
    public function render(CaptchaChallenge $challenge, array $config): string;
}
