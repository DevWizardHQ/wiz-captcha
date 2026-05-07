<?php

it('can return api captcha payload', function () {
    $response = $this->getJson(route('wiz-captcha.api', ['preset' => 'math']));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'key',
            'image',
            'expires_in',
        ]);
});
