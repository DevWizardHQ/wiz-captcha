<?php

namespace DevWizardHQ\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevWizardHQ\Captcha\Captcha
 */
class Captcha extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DevWizardHQ\Captcha\Captcha::class;
    }
}
