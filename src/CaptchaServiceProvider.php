<?php

namespace DevWizardHQ\Captcha;

use DevWizardHQ\Captcha\Commands\CaptchaCommand;
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
            ->hasViews()
            ->hasMigration('create_wiz_captcha_table')
            ->hasCommand(CaptchaCommand::class);
    }
}
