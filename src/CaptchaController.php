<?php

namespace DevWizardHQ\Captcha;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CaptchaController
{
    public function image(?string $preset = null): Response
    {
        $captcha = app(Captcha::class)->create(
            $preset ?? config('wiz-captcha.default', 'default')
        );

        $keys = session('wiz_captcha_keys', []);
        $keys = is_array($keys) ? $keys : [];
        $keys[] = $captcha['key'];

        session([
            'wiz_captcha_key' => $captcha['key'],
            'wiz_captcha_keys' => array_values(array_slice(array_unique(array_filter(
                $keys,
                static fn ($key) => is_string($key) && $key !== '',
            )), -10)),
        ]);

        return response($captcha['image'], 200, [
            'Content-Type' => $captcha['mime'],
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function api(?string $preset = null): JsonResponse
    {
        return response()->json(
            app(Captcha::class)->createApi(
                $preset ?? config('wiz-captcha.default', 'default')
            )
        );
    }
}
