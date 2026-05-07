<?php

use DevWizardHQ\Captcha\CaptchaController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('wiz-captcha.routes.middleware', ['web']))
    ->prefix(config('wiz-captcha.routes.prefix', 'captcha'))
    ->group(function () {
        Route::get('/api/{preset?}', [CaptchaController::class, 'api'])
            ->name('wiz-captcha.api');

        Route::get('/{preset?}', [CaptchaController::class, 'image'])
            ->name('wiz-captcha.image');
    });
