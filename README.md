[![GitHub Workflow Status][ico-tests]][link-tests]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

------

# router

Auto register routes using PHP attributes.

## Requirements

> **Requires [PHP 8.4+](https://php.net/releases/)**

## Installation

```bash
composer require cline/router
```

## Usage

```php
use Cline\Router\Attributes\Get;

final class UsersController
{
    #[Get('users')]
    public function index(): void {}
}

// config/router.php
return [
    'directories' => [
        app_path('Http/Controllers'),
    ],
];
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please use the [GitHub security reporting form][link-security] rather than the issue queue.

## Credits

- [Brian Faust][link-maintainer]
- [All Contributors][link-contributors]

## License

The MIT License. Please see [License File](LICENSE.md) for more information.

[ico-tests]: https://github.com/faustbrian/router/actions/workflows/quality-assurance.yaml/badge.svg
[ico-version]: https://img.shields.io/packagist/v/cline/router.svg
[ico-license]: https://img.shields.io/badge/License-MIT-green.svg
[ico-downloads]: https://img.shields.io/packagist/dt/cline/router.svg

[link-tests]: https://github.com/faustbrian/router/actions
[link-packagist]: https://packagist.org/packages/cline/router
[link-downloads]: https://packagist.org/packages/cline/router
[link-security]: https://github.com/faustbrian/router/security
[link-maintainer]: https://github.com/faustbrian
[link-contributors]: ../../contributors
