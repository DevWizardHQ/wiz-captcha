<?php

it('can return captcha image response', function () {
    $response = $this->get(route('wiz-captcha.image'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');
});
