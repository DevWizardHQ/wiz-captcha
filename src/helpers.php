<?php

use DevWizardHQ\Captcha\Captcha;
use Illuminate\Support\HtmlString;

if (! function_exists('wiz_captcha_src')) {
    function wiz_captcha_src(string $preset = 'default'): string
    {
        return route('wiz-captcha.image', ['preset' => $preset]).'?'.bin2hex(random_bytes(4));
    }
}

if (! function_exists('wiz_captcha_img')) {
    function wiz_captcha_img(string $preset = 'default', array $attributes = []): HtmlString
    {
        $html = '<img src="'.e(wiz_captcha_src($preset)).'" alt="CAPTCHA"';

        foreach ($attributes as $key => $value) {
            $html .= ' '.e($key).'="'.e($value).'"';
        }

        $html .= '>';

        return new HtmlString($html);
    }
}

if (! function_exists('wiz_captcha_api')) {
    function wiz_captcha_api(string $preset = 'default'): array
    {
        return app(Captcha::class)->createApi($preset);
    }
}
