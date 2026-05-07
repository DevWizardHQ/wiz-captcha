# This is my package wiz-captcha

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devwizardhq/wiz-captcha.svg?style=flat-square)](https://packagist.org/packages/devwizardhq/wiz-captcha)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/devwizardhq/wiz-captcha/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/devwizardhq/wiz-captcha/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/devwizardhq/wiz-captcha/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/devwizardhq/wiz-captcha/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/devwizardhq/wiz-captcha.svg?style=flat-square)](https://packagist.org/packages/devwizardhq/wiz-captcha)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/wiz-captcha.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/wiz-captcha)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require devwizardhq/wiz-captcha
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="wiz-captcha-config"
```

## Usage

### In View

```php
<form method="POST" action="/contact">
    @csrf

    {!! wiz_captcha_img('default', ['id' => 'captcha-image']) !!}

    <button type="button" onclick="refreshCaptcha()">Refresh</button>

    <input type="text" name="captcha" required autocomplete="off">

    @error('captcha')
        <div>{{ $message }}</div>
    @enderror

    <button type="submit">Submit</button>
</form>

<script>
function refreshCaptcha() {
    document.getElementById('captcha-image').src = "{{ route('wiz-captcha.image') }}?" + Date.now();
}
</script>
```

### Controller

```php
use DevWizardHQ\Captcha\Rules\CaptchaRule;

$request->validate([
    'captcha' => ['required', new CaptchaRule],
]);
```

### String Rule

```php
$request->validate([
    'captcha' => ['required', 'wiz_captcha'],
]);
```

### API Rule

```php
$captcha = wiz_captcha_api('math');
```

### API Validation

```php
use DevWizardHQ\Captcha\Rules\CaptchaApiRule;

$request->validate([
    'captcha_key' => ['required', 'string'],
    'captcha' => ['required', new CaptchaApiRule($request->captcha_key)],
]);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Shazeedul](https://github.com/DevWizardHQ)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
