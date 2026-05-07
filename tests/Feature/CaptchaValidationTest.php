<?php

use DevWizardHQ\Captcha\Contracts\CaptchaStore;
use DevWizardHQ\Captcha\Rules\CaptchaRule;
use Illuminate\Support\Facades\Validator;

it('can validate against any active session captcha key', function () {
    $store = app(CaptchaStore::class);
    $hasher = app('hash');

    $store->put('first-key', [
        'answer_hash' => $hasher->make('first'),
        'case_sensitive' => false,
    ], 300);

    $store->put('second-key', [
        'answer_hash' => $hasher->make('second'),
        'case_sensitive' => false,
    ], 300);

    session([
        'wiz_captcha_key' => 'second-key',
        'wiz_captcha_keys' => ['first-key', 'second-key'],
    ]);

    $validator = Validator::make(
        ['captcha' => 'first'],
        ['captcha' => [new CaptchaRule]]
    );

    expect($validator->passes())->toBeTrue()
        ->and(session('wiz_captcha_keys'))->toBe(['second-key']);
});

it('does not extend captcha expiration when attempts are incremented', function () {
    $store = app(CaptchaStore::class);

    $store->put('captcha-key', [
        'answer_hash' => app('hash')->make('secret'),
        'case_sensitive' => false,
    ], 300);

    $expiresAt = $store->get('captcha-key')['expires_at'];

    $store->incrementAttempts('captcha-key');

    expect($store->get('captcha-key'))
        ->attempts->toBe(1)
        ->expires_at->toBe($expiresAt);
});
