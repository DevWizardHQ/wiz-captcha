<?php

use DevWizardHQ\Captcha\Captcha;

it('can generate a captcha image', function () {
    $captcha = app(Captcha::class)->create();

    expect($captcha)
        ->toHaveKeys(['key', 'image', 'mime', 'expires_in'])
        ->and($captcha['key'])->toBeString()
        ->and($captcha['image'])->toBeString()
        ->and($captcha['mime'])->toBe('image/png');
});
