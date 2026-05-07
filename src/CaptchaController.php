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

        session(['wiz_captcha_key' => $captcha['key']]);

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
