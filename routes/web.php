<?php

use DevWizardHQ\Captcha\CaptchaController;
use Illuminate\Support\Facades\Route;

if (! config('wiz-captcha.routes.enabled', true)) {
    return;
}

$middleware = config('wiz-captcha.routes.middleware', ['web']);
$throttle = config('wiz-captcha.routes.throttle', null);

if (filled($throttle)) {
    $middleware[] = str_contains($throttle, ':') ? $throttle : "throttle:{$throttle}";
}

Route::middleware($middleware)
    ->prefix(config('wiz-captcha.routes.prefix', 'captcha'))
    ->group(function () {
        Route::get('/api/{preset?}', [CaptchaController::class, 'api'])
            ->name('wiz-captcha.api');

        Route::get('/{preset?}', [CaptchaController::class, 'image'])
            ->name('wiz-captcha.image');
    });
