<?php

namespace DevWizardHQ\Captcha\Contracts;

use DevWizardHQ\Captcha\CaptchaChallenge;

interface ChallengeGenerator
{
    public function generate(array $config): CaptchaChallenge;
}
