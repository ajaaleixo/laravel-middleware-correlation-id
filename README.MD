# Laravel Correlation ID Middleware
Supports L5.5+

This package offers you a way of adding a correlation id output on Laravel Logger based on a sent Header.
Inspired by [this repo](https://github.com/proemergotech/correlate-php-laravel) it has:

- all the functionality to set macros on a Request and Push a Monolog Processor
- adds a configuration file to override settings
- adds proper tests
- enables usage for recent Laravel versions (5.5+)

## Install

### Add Service provider
Open your config/app.php and insert the following in providers key:
```
Ajaaleixo\Middleware\CorrelationId\CorrelationIdServiceProvider::class,
```

### Run Publish command
```
php artisan vendor:publish --provider="Ajaaleixo\Middleware\CorrelationId\CorrelationIdServiceProvider"
```

### Edit your settings
Open ```config/correlationid.php```.

## Roadmap
TODO


