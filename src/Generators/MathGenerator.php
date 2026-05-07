<?php

namespace DevWizardHQ\Captcha\Generators;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ChallengeGenerator;
use InvalidArgumentException;

class MathGenerator implements ChallengeGenerator
{
    public function generate(array $config): CaptchaChallenge
    {
        $min = (int) ($config['min'] ?? 1);
        $max = (int) ($config['max'] ?? 20);
        $operators = $config['operators'] ?? ['+', '-'];

        $a = random_int($min, $max);
        $b = random_int($min, $max);
        $operator = $operators[random_int(0, count($operators) - 1)];

        if ($operator === '-' && $b > $a) {
            [$a, $b] = [$b, $a];
        }

        $answer = match ($operator) {
            '+' => $a + $b,
            '-' => $a - $b,
            '*' => $a * $b,
            default => throw new InvalidArgumentException("Unsupported CAPTCHA operator [$operator]."),
        };

        return new CaptchaChallenge(
            id: bin2hex(random_bytes(16)),
            question: "{$a} {$operator} {$b} =",
            answer: (string) $answer,
        );
    }
}
