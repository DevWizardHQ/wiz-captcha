# Changelog

All notable changes to `wiz-captcha` will be documented in this file.

## v1.1.0 - 2026-10-30

### Changed

- Requires Laravel 12 or 13. Laravel 11 is past end of life and Composer refuses to install it, because all of its versions have open security advisories. Apps still on Laravel 11 keep resolving to 1.0.1.

### Maintenance

- `actions/checkout` 6 to 7.
- Removed the `dependabot-auto-merge` workflow; Dependabot updates are now reviewed by hand.
- Limited the `GITHUB_TOKEN` in the PHPStan and test workflows to `contents: read`.
- Removed the repository git hooks. Force pushes are blocked by a branch ruleset and secret scanning with push protection is on.
- Git hook files are excluded from the Composer archive.

**Full Changelog**: https://github.com/DevWizardHQ/wiz-captcha/compare/1.0.1...1.1.0

## 1.0.1 - 2026-05-10

**Full Changelog**: https://github.com/DevWizardHQ/wiz-captcha/compare/1.0.0...1.0.1

## 1.0.0 - 2026-05-10

### What's Changed

* Remove deprecated `imagedestroy()` usage causing PHPStan failure by @Copilot in https://github.com/DevWizardHQ/wiz-captcha/pull/3
* fix: normalize captcha display text to lowercase when case_sensitive is false by @Copilot in https://github.com/DevWizardHQ/wiz-captcha/pull/2

### New Contributors

* @Copilot made their first contribution in https://github.com/DevWizardHQ/wiz-captcha/pull/3

**Full Changelog**: https://github.com/DevWizardHQ/wiz-captcha/compare/0.0.2...1.0.0

## 0.0.2 - 2026-05-07

**Full Changelog**: https://github.com/DevWizardHQ/wiz-captcha/compare/0.0.1...0.0.2

## 0.0.1 - 2026-05-07

**Full Changelog**: https://github.com/DevWizardHQ/wiz-captcha/commits/0.0.1
