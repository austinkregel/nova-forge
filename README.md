# A Nova tool to also display forge information
Redis based storage, and powered by [forge.laravel.com](https://forge.laravel.com)

All you will need is a subscription from Laravel Forge, and Predis for key persistence.


[![Latest Version on Packagist](https://img.shields.io/packagist/v/kregel/nova-forge.svg?style=flat-square)](https://packagist.org/packages/kregel/nova-forge)
[![Total Downloads](https://img.shields.io/packagist/dt/kregel/nova-forge.svg?style=flat-square)](https://packagist.org/packages/kregel/nova-forge)

![Screenshot](https://github.com/austinkregel/nova-forge/raw/master/screenshot.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require predis/predis
```
```bash
composer require kregel/nova-forge
```

Next up, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

## Usage

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \Kregel\NovaForge\NovaForge,
    ];
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

TODO:
#### Servers
 - [X] List the servers with basic information
 - [ ] Run recipes per server.
 - [X] Put the servers in the left hand nav
 - [ ] Deploy new servers
 - [ ] CRUD Daemons
 - [ ] CRUD Network/Firewall
 - [ ] Update server meta
 
### Sites
 - [ ] Add new Sites
 - [ ] Deploy SSH Certs
 - [ ] Add new Queue workers
 - [ ] Update site meta
 
### Security

If you discover any security related issues, please email github@austinkregel.com instead of using the issue tracker.

## Credits

- [Austin kregel](https://github.com/austinkregel)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support on Beerpay
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/austinkregel/nova-weather-cards/badge.svg?style=beer-square)](https://beerpay.io/austinkregel/nova-weather-cards)  [![Beerpay](https://beerpay.io/austinkregel/nova-weather-cards/make-wish.svg?style=flat-square)](https://beerpay.io/austinkregel/nova-weather-cards?focus=wish)
