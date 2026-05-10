<?php

use Illuminate\Support\Facades\Route;

it('can return captcha image response', function () {
    $response = $this->get(route('wiz-captcha.image'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');
});

it('applies throttle middleware to captcha routes by default', function () {
    $imageRoute = Route::getRoutes()->getByName('wiz-captcha.image');
    $apiRoute = Route::getRoutes()->getByName('wiz-captcha.api');

    expect($imageRoute->gatherMiddleware())
        ->toContain('throttle:60,1')
        ->and($apiRoute->gatherMiddleware())
        ->toContain('throttle:60,1');
});
