<?php

namespace DevWizardHQ\Captcha\Contracts;

interface CaptchaStore
{
    public function put(string $id, array $payload, int $ttl): void;

    public function get(string $id): ?array;

    public function forget(string $id): void;

    public function incrementAttempts(string $id): int;
}
