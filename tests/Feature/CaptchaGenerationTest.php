<?php

use DevWizardHQ\Captcha\Captcha;
use DevWizardHQ\Captcha\Contracts\CaptchaStore;
use DevWizardHQ\Captcha\Generators\TextGenerator;

it('can generate a captcha image', function () {
    $captcha = app(Captcha::class)->create();

    expect($captcha)
        ->toHaveKeys(['key', 'image', 'mime', 'expires_in'])
        ->and($captcha['key'])->toBeString()
        ->and($captcha['image'])->toBeString()
        ->and($captcha['mime'])->toBe('image/png');
});

it('displays lowercase text in the captcha image when case_sensitive is false', function () {
    config(['wiz-captcha.case_sensitive' => false]);

    $challenge = app(TextGenerator::class)->generate(config('wiz-captcha.presets.default'));

    expect($challenge->question)->toBe(mb_strtolower($challenge->question));
});

it('preserves original casing in the captcha image when case_sensitive is true', function () {
    config(['wiz-captcha.case_sensitive' => true]);

    $challenge = app(TextGenerator::class)->generate(array_merge(
        config('wiz-captcha.presets.default'),
        ['characters' => 'ABC', 'length' => 3],
    ));

    expect($challenge->question)->toBe($challenge->answer)
        ->and($challenge->question)->toBe(mb_strtoupper($challenge->question));
});

it('accepts uppercase input when case_sensitive is false', function () {
    $store = app(CaptchaStore::class);
    $hasher = app('hash');

    $store->put('test-key', [
        'answer_hash' => $hasher->make('abc'),
        'case_sensitive' => false,
    ], 300);

    expect(app(Captcha::class)->verify('test-key', 'ABC'))->toBeTrue();
});

it('rejects wrong-case input when case_sensitive is true', function () {
    $store = app(CaptchaStore::class);
    $hasher = app('hash');

    $store->put('test-key-cs', [
        'answer_hash' => $hasher->make('AbC'),
        'case_sensitive' => true,
    ], 300);

    expect(app(Captcha::class)->verify('test-key-cs', 'ABC'))->toBeFalse();
});
