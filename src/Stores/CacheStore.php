<?php

namespace DevWizardHQ\Captcha\Stores;

use DevWizardHQ\Captcha\Contracts\CaptchaStore;
use Illuminate\Contracts\Cache\Repository;

class CacheStore implements CaptchaStore
{
    public function __construct(
        private readonly Repository $cache,
    ) {}

    private function key(string $id): string
    {
        return "wiz-captcha:{$id}";
    }

    public function put(string $id, array $payload, int $ttl): void
    {
        $payload['attempts'] = 0;
        $payload['ttl'] = $ttl;
        $payload['expires_at'] = time() + $ttl;

        $this->cache->put($this->key($id), $payload, $ttl);
    }

    public function get(string $id): ?array
    {
        $record = $this->cache->get($this->key($id));

        return is_array($record) ? $record : null;
    }

    public function forget(string $id): void
    {
        $this->cache->forget($this->key($id));
    }

    public function incrementAttempts(string $id): int
    {
        $record = $this->get($id);

        if (! $record) {
            return 0;
        }

        $record['attempts'] = ($record['attempts'] ?? 0) + 1;
        $expiresAt = (int) ($record['expires_at'] ?? (time() + (int) ($record['ttl'] ?? 300)));
        $remainingTtl = $expiresAt - time();

        if ($remainingTtl <= 0) {
            $this->forget($id);

            return $record['attempts'];
        }

        $this->cache->put($this->key($id), $record, $remainingTtl);

        return $record['attempts'];
    }
}
