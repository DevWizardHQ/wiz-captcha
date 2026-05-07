<?php

namespace DevWizardHQ\Captcha;

use DevWizardHQ\Captcha\Contracts\CaptchaStore;
use DevWizardHQ\Captcha\Contracts\ImageRenderer;
use DevWizardHQ\Captcha\Renderers\GdRenderer;
use DevWizardHQ\Captcha\Stores\CacheStore;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CaptchaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('wiz-captcha')
            ->hasConfigFile()
            ->hasRoutes(['web']);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(CaptchaStore::class, function ($app) {
            return new CacheStore(
                $app['cache']->store(config('wiz-captcha.cache_store'))
            );
        });

        $this->app->singleton(ImageRenderer::class, GdRenderer::class);

        $this->app->singleton(Captcha::class, function ($app) {
            return new Captcha(
                $app->make(CaptchaStore::class),
                $app->make(ImageRenderer::class),
                $app['hash'],
            );
        });

        $this->app->alias(Captcha::class, 'wiz-captcha');
    }

    public function packageBooted(): void
    {
        Validator::extend('wiz_captcha', function ($attribute, $value) {
            $key = session('wiz_captcha_key');

            return $key && app(Captcha::class)->verify($key, (string) $value);
        });

        Validator::extend('wiz_captcha_api', function ($attribute, $value, $parameters) {
            $key = $parameters[0] ?? null;

            return $key && app(Captcha::class)->verify($key, (string) $value);
        });
    }
}
