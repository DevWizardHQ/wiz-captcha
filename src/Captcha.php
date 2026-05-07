<?php

namespace DevWizardHQ\Captcha;

use DevWizardHQ\Captcha\Contracts\CaptchaStore;
use DevWizardHQ\Captcha\Contracts\ImageRenderer;
use DevWizardHQ\Captcha\Generators\MathGenerator;
use DevWizardHQ\Captcha\Generators\NumberGenerator;
use DevWizardHQ\Captcha\Generators\TextGenerator;
use Illuminate\Contracts\Hashing\Hasher;

class Captcha
{
    public function __construct(
        private readonly CaptchaStore $store,
        private readonly ImageRenderer $renderer,
        private readonly Hasher $hasher,
    ) {}

    public function create(string $preset = 'default'): array
    {
        $config = $this->getPreset($preset);

        $challenge = $this->getGenerator($config)->generate($config);

        $answer = $this->normalize($challenge->answer, $challenge->caseSensitive);
        $ttl = (int) config('wiz-captcha.expire', 300);

        $this->store->put($challenge->id, [
            'answer_hash' => $this->hasher->make($answer),
            'case_sensitive' => $challenge->caseSensitive,
            'preset' => $preset,
        ], $ttl);

        return [
            'key' => $challenge->id,
            'image' => $this->renderer->render($challenge, $config),
            'mime' => 'image/png',
            'expires_in' => $ttl,
        ];
    }

    public function createApi(string $preset = 'default'): array
    {
        $captcha = $this->create($preset);

        return [
            'key' => $captcha['key'],
            'image' => 'data:'.$captcha['mime'].';base64,'.base64_encode($captcha['image']),
            'expires_in' => $captcha['expires_in'],
        ];
    }

    public function verify(string $key, string $input): bool
    {
        if (! config('wiz-captcha.enabled', true)) {
            return true;
        }

        $record = $this->store->get($key);

        if (! $record) {
            return false;
        }

        $maxAttempts = (int) config('wiz-captcha.max_attempts', 5);

        if (($record['attempts'] ?? 0) >= $maxAttempts) {
            $this->store->forget($key);

            return false;
        }

        $caseSensitive = (bool) ($record['case_sensitive'] ?? false);
        $normalizedInput = $this->normalize($input, $caseSensitive);

        $valid = $this->hasher->check($normalizedInput, $record['answer_hash']);

        if ($valid) {
            $this->store->forget($key);

            return true;
        }

        $attempts = $this->store->incrementAttempts($key);

        if ($attempts >= $maxAttempts) {
            $this->store->forget($key);
        }

        return false;
    }

    private function normalize(string $value, bool $caseSensitive): string
    {
        $value = preg_replace('/\s+/', '', trim($value)) ?? '';

        return $caseSensitive ? $value : mb_strtolower($value);
    }

    private function getPreset(string $preset): array
    {
        return config("wiz-captcha.presets.{$preset}")
            ?? config('wiz-captcha.presets.default');
    }

    private function getGenerator(array $config): object
    {
        return match ($config['type'] ?? 'text') {
            'number' => new NumberGenerator,
            'math' => new MathGenerator,
            default => new TextGenerator,
        };
    }
}
