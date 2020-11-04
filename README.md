# Laravel Security

## Installation

> Package requires Laravel 7 or higher, and PHP 7.0+.

You may use Composer to install package into your Laravel project:

    composer require Diviky/laravel-security

### Configuration

After installing the package, publish its config, migration and view, using the `vendor:publish` Artisan command:

    php artisan vendor:publish --provider="Diviky\Security\SecurityServiceProvider"

Next, you need to migrate your database. The Laravel Authentication Log migration will create the table your application needs to store authentication logs:

    php artisan migrate

#### Avaliable Middlewars

```php

'firewall.blacklist'
'firewall.whitelist'
'firewall.attacks'
'2fa'
'2fa.remember'
'2fa.stateless'
'security.password' // Check is password very older
'security.headers' // Add headers

```

## License

Laravel Authentication Log is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
